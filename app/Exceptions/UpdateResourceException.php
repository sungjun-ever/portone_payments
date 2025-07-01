<?php

namespace App\Exceptions;


use Exception;
use Illuminate\Support\Facades\Log;

class UpdateResourceException extends Exception
{
    public function report()
    {
        Log::error($this->getMessage(), request()->all());
    }

    public function render()
    {
        return response()->json([
            'result' => 'error',
            'message' => 'UPDATE_RESOURCE_ERROR'
        ], 500);
    }
}