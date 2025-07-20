<?php

namespace App\Contracts\Warehouse;

use Illuminate\Pagination\LengthAwarePaginator;

interface WarehouseServiceInterface
{
    public function getWarehousesWithStockInfo(int $perPage = 10): LengthAwarePaginator;
}