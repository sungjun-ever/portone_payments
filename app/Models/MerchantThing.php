<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MerchantThing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'merchant_id',
        'name',
        'description',
        'price'
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }
}
