<?php

namespace App\Services\Warehouse;

use App\Contracts\Warehouse\WarehouseServiceInterface;
use App\Repositories\Interfaces\Warehouse\WarehouseRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

final class WarehouseService implements WarehouseServiceInterface
{
    public function __construct(
        private WarehouseRepositoryInterface $warehouseRepository
    )
    {
    }

    public function getWarehousesWithStockInfo(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        $perPage = max(1, min(100, $perPage));

        return $this->warehouseRepository->getWarehousesWithStockInfo($perPage, $filters);
    }
}