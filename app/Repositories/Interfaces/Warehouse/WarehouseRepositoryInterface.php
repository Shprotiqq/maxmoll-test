<?php

namespace App\Repositories\Interfaces\Warehouse;

use Illuminate\Pagination\LengthAwarePaginator;

interface WarehouseRepositoryInterface
{
    public function getWarehousesWithStockInfo(int $perPage = 10, array $filters = []): LengthAwarePaginator;
}