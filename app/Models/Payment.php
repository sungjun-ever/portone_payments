<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'pg_transaction_id',
        'amount',
        'status',
        'payment_method',
        'paid_at',
        'failed_reason'
    ];

    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }

    public function transaction(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }
}
