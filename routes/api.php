<?php

use App\Http\Controllers\Api\CancelOrderController;
use App\Http\Controllers\Api\CompleteOrderController;
use App\Http\Controllers\Api\CreateOrderController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ResumeOrderController;
use App\Http\Controllers\Api\UpdateOrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WarehouseController;

Route::get('/warehouses', WarehouseController::class);
Route::get('/products', ProductController::class);
Route::get('/orders', OrderController::class);
Route::post('/orders', CreateOrderController::class);
Route::put('/orders/{order}', UpdateOrderController::class);
Route::patch('orders/{order}/complete', CompleteOrderController::class);
Route::patch('/orders/{order}/cancel', CancelOrderController::class);
Route::patch('orders/{order}/resume', ResumeOrderController::class);