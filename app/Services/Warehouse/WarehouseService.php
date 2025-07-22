<?php

namespace App\Services\Warehouse;

use App\Contracts\Warehouse\WarehouseServiceInterface;
use App\DTOs\FiltersDTO;
use App\Repositories\Interfaces\Warehouse\WarehouseRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class WarehouseService implements WarehouseServiceInterface
{
    public function __construct(
        private WarehouseRepositoryInterface $warehouseRepository
    )
    {
    }

    public function getWarehousesWithStockInfo(FiltersDTO $dto): LengthAwarePaginator
    {
        return $this->warehouseRepository->getWarehousesWithStockInfo($dto);
    }
}