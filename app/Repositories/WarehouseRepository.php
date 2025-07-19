<?php

namespace App\Repositories;

use App\DTOs\WarehouseDTO;
use App\Models\Warehouse;
use App\Repositories\Interfaces\WarehouseRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class WarehouseRepository implements Interfaces\WarehouseRepositoryInterface
{
    public function getAllWarehouses(int $perPage = 10): LengthAwarePaginator
    {
        $warehouses = Warehouse::query()->paginate($perPage);

        $warehouses->getCollection()->transform(function (Warehouse $warehouse) {
           return new WarehouseDTO(
               id: $warehouse->id,
               name: $warehouse->name,
           );
        });

        return $warehouses;
    }
}