<?php


use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WarehouseController;

Route::get('/warehouses', WarehouseController::class);
Route::get('/products', ProductController::class);