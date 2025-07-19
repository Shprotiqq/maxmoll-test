<?php

namespace App\Services;

use App\Repositories\Interfaces\WarehouseRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class WarehouseService
{
    public function __construct(
        private WarehouseRepositoryInterface $warehouseRepository
    )
    {
    }

    public function getWarehouses(int $perPage = 10): LengthAwarePaginator
    {
        $perPage = max(1, min(100, $perPage));

        return $this->warehouseRepository->getAllWarehouses($perPage);
    }
}