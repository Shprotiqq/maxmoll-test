<?php

namespace App\Contracts\Warehouse;

use App\DTOs\FiltersDTO;
use Illuminate\Pagination\LengthAwarePaginator;

interface WarehouseServiceInterface
{
    public function getWarehousesWithStockInfo(FiltersDTO $dto): LengthAwarePaginator;
}