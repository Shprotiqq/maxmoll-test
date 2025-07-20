<?php

namespace App\Repositories\Interfaces\Warehouse;

use App\DTOs\FiltersDTO;
use Illuminate\Pagination\LengthAwarePaginator;

interface WarehouseRepositoryInterface
{
    public function getWarehousesWithStockInfo(FiltersDTO $dto): LengthAwarePaginator;
}