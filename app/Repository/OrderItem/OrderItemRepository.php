<?php

namespace App\Repository\OrderItem;

use App\Models\OrderItem;
use App\Repository\BaseRepository;

class OrderItemRepository extends BaseRepository implements OrderItemRepositoryInterface
{
    public function __construct()
    {
        $this->model = new OrderItem();
    }
}