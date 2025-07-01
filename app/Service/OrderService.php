<?php

namespace App\Service;

use App\Exceptions\CreateResourceException;
use App\Exceptions\OrderCreationException;
use App\Exceptions\OrderItemCreationException;
use App\Repository\Order\OrderRepositoryInterface;
use App\Repository\OrderItem\OrderItemRepositoryInterface;
use Illuminate\Support\Facades\DB;

readonly class OrderService
{
    public function __construct(
        private OrderRepositoryInterface     $orderRepository,
        private OrderItemRepositoryInterface $orderItemRepository
    )
    {
    }

    //@TODO: 재고 체크 방식 추가해야함
    public function storeOrder(array $order, array $orderItems): int
    {
        return DB::transaction(function () use ($order, $orderItems) {
            $insertOrder = $this->orderRepository->create($order);

            if (!$insertOrder) {
                throw new OrderCreationException("주문 정보 생성 실패");
            }

            $orderId = $insertOrder->id;

            $processedOrderItems = collect($orderItems)->map(function ($item) use ($orderId) {
                $item['order_id'] = $orderId;
                return $item;
            })->toArray();

            $insertOrderItemCount = 0;
            foreach ($processedOrderItems as $item) {
                if ($this->orderItemRepository->create($item)) {
                    $insertOrderItemCount++;
                }
            }

            if ($insertOrderItemCount !== count($processedOrderItems)) {
                throw new OrderItemCreationException("주문 항목 생성 실패");
            }

            return $orderId;
        });


    }

    public function cancelOrder()
    {

    }
}