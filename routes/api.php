<?php

use App\Http\Controllers\Api\CreateOrderController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UpdateOrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WarehouseController;

Route::get('/warehouses', WarehouseController::class);
Route::get('/products', ProductController::class);
Route::get('/orders', OrderController::class);
Route::post('/orders', CreateOrderController::class);
Route::put('/orders/{order}', UpdateOrderController::class);