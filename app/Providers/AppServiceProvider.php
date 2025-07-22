<?php

namespace App\Providers;

use App\Contracts\Order\OrderServiceInterface;
use App\Contracts\Product\ProductServiceInterface;
use App\Contracts\StockMovement\StockMovementServiceInterface;
use App\Contracts\Warehouse\WarehouseServiceInterface;
use App\Repositories\Interfaces\Order\OrderRepositoryInterface;
use App\Repositories\Interfaces\Product\ProductRepositoryInterface;
use App\Repositories\Interfaces\StockMovement\StockMovementRepositoryInterface;
use App\Repositories\Interfaces\Warehouse\WarehouseRepositoryInterface;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\StockMovement\StockMovementRepository;
use App\Repositories\Warehouse\WarehouseRepository;
use App\Services\Order\OrderService;
use App\Services\Product\ProductService;
use App\Services\StockMovement\StockMovementService;
use App\Services\Warehouse\WarehouseService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            WarehouseServiceInterface::class,
            WarehouseService::class
        );

        $this->app->bind(
            WarehouseRepositoryInterface::class,
            WarehouseRepository::class
        );

        $this->app->bind(
            ProductServiceInterface::class,
            ProductService::class
        );

        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class
        );

        $this->app->bind(
            OrderServiceInterface::class,
            OrderService::class
        );

        $this->app->bind(
            OrderRepositoryInterface::class,
            OrderRepository::class
        );

        $this->app->bind(
            StockMovementServiceInterface::class,
            StockMovementService::class
        );

        $this->app->bind(
            StockMovementRepositoryInterface::class,
            StockMovementRepository::class
        );
    }

    public function boot(): void
    {
    }
}
