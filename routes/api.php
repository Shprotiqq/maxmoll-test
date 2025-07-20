<?php

use App\Http\Controllers\Api\Order\CancelOrderController;
use App\Http\Controllers\Api\Order\CompleteOrderController;
use App\Http\Controllers\Api\Order\CreateOrderController;
use App\Http\Controllers\Api\Order\IndexOrderController;
use App\Http\Controllers\Api\Order\ResumeOrderController;
use App\Http\Controllers\Api\Order\UpdateOrderController;
use App\Http\Controllers\Api\Product\IndexProductController;
use App\Http\Controllers\Api\Warehouse\IndexWarehouseController;
use Illuminate\Support\Facades\Route;


Route::prefix('orders')->group(function () {
    Route::get('/', [IndexOrderController::class, 'getOrders']);
    Route::post('/', [CreateOrderController::class, 'createOrder']);
    Route::put('/{orderId}', [UpdateOrderController::class, 'updateOrder']);
    Route::patch('/{orderId}/complete', [CompleteOrderController::class, 'completeOrder']);
    Route::patch('/{orderId}/cancel', [CancelOrderController::class, 'cancelOrder']);
    Route::patch('/{orderId}/resume', [ResumeOrderController::class, 'resumeOrder']);
});

Route::prefix('products')->group(function () {
    Route::get('/', [IndexProductController::class, 'getProducts']);
});

Route::prefix('warehouses')->group(function () {
    Route::get('/', [IndexWarehouseController::class, 'getWarehouses']);
});

