<?php

namespace App\Jobs;

use App\Exceptions\OrderCreationException;
use App\Exceptions\OrderItemCreationException;
use App\Repository\Order\OrderRepositoryInterface;
use App\Repository\OrderItem\OrderItemRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class ProcessOrderCreation implements ShouldQueue
{
    use Queueable;

    public array $orderData;
    public array $orderItemsData;

    // 재시도 횟수
    public int $tries = 3;

    // 재시도 시에 간격
    public array $backOff = [5, 10, 15];

    /**
     * Create a new job instance.
     */
    public function __construct(array $orderData, array $orderItemsData)
    {
        $this->orderData = $orderData;
        $this->orderItemsData = $orderItemsData;
    }

    /**
     * Execute the job.
     */
    public function handle(
        OrderRepositoryInterface $orderRepository,
        OrderItemRepositoryInterface $orderItemRepository
    ): void
    {
        Log::info("ProcessOrderCreation", ['order_data' => $this->orderData]);

        try {
            DB::transaction(function () use ($orderRepository, $orderItemRepository) {
               $insertOrder = $orderRepository->create($this->orderData);

                if (!$insertOrder) {
                    throw new OrderCreationException("주문 기본 정보 생성 실패.");
                }

                $orderId = $insertOrder->id;

                $processedOrderItems = collect($this->orderItemsData)->map(function ($item) use ($orderId) {
                    $item['order_id'] = $orderId;
                    return $item;
                })->toArray();

                $insertOrderItemCount = 0;
                foreach ($processedOrderItems as $item) {
                    if ($orderItemRepository->create($item)) {
                        $insertOrderItemCount++;
                    }
                }

                if ($insertOrderItemCount !== count($processedOrderItems)) {
                    throw new OrderItemCreationException("주문 항목 생성 실패.");
                }
            });
        } catch (\Throwable $e) {
            Log::error("ProcessOrderCreation 실패: " . $e->getMessage(), [
                'order_data' => $this->orderData,
                'error' => $e->getTraceAsString()
            ]);

            // 레디스 재고 원복
            $this->compensateRedisStock();

            $this->fail($e);
        }
    }

    /**
     * 저장 실패한 경우 레디스 원복 로직
     * @return void
     */
    protected function compensateRedisStock(): void
    {
        foreach ($this->orderItemsData as $item) {
            $merchantThingId = $item['thing_id'];
            $orderedQuantity = $item['quantity'];
            $redisKey = "merchant_thing_stock:{$merchantThingId}";

            try {
                // 저장이 실패로 레디스에서 차감했던 재고를 원복
                Redis::incrby($redisKey, $orderedQuantity);
                Log::info("레디스 재고 원복 성공", ['thing_id' => $merchantThingId, 'quantity' => $orderedQuantity]);
            } catch (\Exception $e) {
                Log::error("레디스 재고 원복 실패: " . $e->getMessage(), [
                    'merchant_thing_id' => $merchantThingId,
                    'quantity' => $orderedQuantity,
                    'error' => $e->getTraceAsString()
                ]);

                // 레디스 원복 실패의 경우 알림을 보내는 로직 필요함
            }
        }
    }

    /**
     * job 실패 시 실행 메서드
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception): void
    {
        Log::critical("ProcessOrderCreation Job 최종 실패: " . $exception->getMessage(), [
            'order_data' => $this->orderData,
            'exception' => $exception->getTraceAsString()
        ]);

        // 최종 실패 알림을 보내는 로직 필요함
    }
}
