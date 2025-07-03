<?php

namespace App\Service;

use App\Exceptions\StockException;
use App\Jobs\ProcessOrderCreation;
use App\Repository\Order\OrderRepositoryInterface;
use App\Repository\OrderItem\OrderItemRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

readonly class OrderService
{
    public function __construct(
        private OrderRepositoryInterface     $orderRepository,
        private OrderItemRepositoryInterface $orderItemRepository
    )
    {
    }

    public function storeOrder(array $order, array $orderItems): int
    {
        return DB::transaction(function () use ($order, $orderItems) {
            foreach ($orderItems as $item) {
                $merchantThingId = $item['thingId'];
                $orderedQuantity = $item['quantity'];
                $redisKey = "merchant_thing_stock:{$merchantThingId}";

                $luaScript = "
                    local current_stock = redis.call('GET', KEYS[1])
                    if current_stock then
                        if tonumber(current_stock) >= tonumber(ARGV[1]) then
                            return redis.call('DECRBY', KEYS[1], ARGV[1])
                        end
                    end
                    return -1
                ";

                $result = Redis::eval($luaScript, 1, $redisKey, $orderedQuantity);

                if ($result === -1) {
                    // 재고 부족 시 예외 발생 (이 시점에서 사용자에게 즉시 알릴 수 있습니다)
                    throw new StockException("merchant_thing_id: {$merchantThingId})의 재고가 부족합니다.");
                }
            }

            ProcessOrderCreation::dispatch($order, $orderItems);

            return true;
        });


    }

    public function cancelOrder()
    {

    }
}