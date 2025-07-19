<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WarehouseController;

Route::get('/warehouses', WarehouseController::class);