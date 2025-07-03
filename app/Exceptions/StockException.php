<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class StockException extends Exception
{
    public function report(): void
    {
        Log::error($this->getMessage(), request()->all());
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'result' => 'error',
            'message' => 'STOCK_NOT_AVAILABLE',
        ], 400);
    }
}
