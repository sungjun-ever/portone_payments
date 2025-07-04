<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CreateResourceException extends Exception
{
    public function report()
    {
        Log::error($this->getMessage(), request()->all());
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'result' => 'error',
            'message' => 'CREATE_RESOURCE_ERROR'
        ], 500);
    }
}
