<?php

namespace App\Repositories\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface WarehouseRepositoryInterface
{
    public function getAllWarehouses(int $perPage = 10): LengthAwarePaginator;
}