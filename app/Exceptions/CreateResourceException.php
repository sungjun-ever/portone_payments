<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CreateResourceException extends Exception
{
    public function report()
    {
        Log::error(
            request()->distinct . ': CreateResourceException' .
            "-> request" . json_encode(request()->all(), JSON_UNESCAPED_UNICODE) .
            "-> message" . $this->getMessage()
        );
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => 'Create Resource Error'
        ], 500);
    }
}
