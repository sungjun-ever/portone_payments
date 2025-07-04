<?php

use App\Http\Controllers\MerchantController;
use App\Http\Controllers\MerchantThingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::prefix('/stores')->group(function () {
        Route::get('/', [MerchantController::class, 'index']);
        Route::post('/', [MerchantController::class, 'store']);
        Route::get('/{id}', [MerchantController::class, 'show'])
            ->where('id', '[0-9]+');
        Route::put('/{id}', [MerchantController::class, 'update']);
        Route::delete('/{id}', [MerchantController::class, 'destroy']);

        Route::get('/{id}/things', [MerchantThingController::class, 'index'])
            ->where('id', '[0-9]+');
        Route::post('/{id}/things', [MerchantThingController::class, 'store'])
            ->where('id', '[0-9]+');
        Route::get('/things/{thingId}', [MerchantThingController::class, 'show'])
            ->where('thingId', '[0-9]+');
        Route::put('/things/{thingId}', [MerchantThingController::class, 'update'])
            ->where('thingId', '[0-9]+');
        Route::delete('/things/{thingId}', [MerchantThingController::class, 'destroy'])
            ->where('thingId', '[0-9]+');
    });
})->withoutMiddleware('auth:sanctum');
