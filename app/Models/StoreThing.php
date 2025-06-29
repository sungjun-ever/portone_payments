<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreThing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description', 'price'];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
