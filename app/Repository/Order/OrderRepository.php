<?php

namespace App\Repository\Order;

use App\Models\Order;
use App\Repository\BaseRepository;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function __construct()
    {
        $this->model = new Order();
    }
}