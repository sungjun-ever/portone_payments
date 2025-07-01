<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class OrderItemCreationException extends Exception
{
    public function report(): void
    {
        Log::error($this->getMessage(), request()->all());
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'result' => 'error',
            'message' => 'CREATE_ORDER_ITEM_ERROR'
        ], 500);

    }
}
