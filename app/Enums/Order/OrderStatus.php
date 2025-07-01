<?php

namespace App\Enums\Order;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case CANCELLED = 'cancelled';
    case COMPLETED = 'refunded';
    case FAILED = 'failed';
}