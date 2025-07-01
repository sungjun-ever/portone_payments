<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class OrderCreationException extends Exception
{
    public function report(): void
    {
        Log::error($this->getMessage(), request()->all());
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'result' => 'error',
            'message' => 'CREATE_ORDER_ERROR'
        ], 500);
    }
}
