<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class DeleteResourceException extends Exception
{
    public function report()
    {
        Log::error($this->getMessage(), request()->all());
    }

    public function render()
    {
        return response()->json([
            'result' => 'error',
            'message' => 'DELETE_RESOURCE_ERROR'
        ], 500);
    }
}