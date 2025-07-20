<?php

namespace App\Repositories\Warehouse;

use App\DTOs\Warehouse\WarehouseStockDTO;
use App\Models\Warehouse;
use App\Repositories\Interfaces;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final class WarehouseRepository implements Interfaces\Warehouse\WarehouseRepositoryInterface
{
    public function getWarehousesWithStockInfo(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        $query = Warehouse::with(['stocks.product:id,name']);

        $this->applyFilters($query, $filters);

        $warehouses = $query->paginate($perPage);

        $warehouses->setCollection(
            $this->transformToWarehouseStockDTO($warehouses->getCollection())
        );

        return $warehouses;
    }

    private function applyFilters($query, array $filters): void
    {
        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }
    }

    private function transformToWarehouseStockDTO(Collection $warehouses): Collection
    {
        return $warehouses->map(function (Warehouse $warehouse) {
            $products = $warehouse->stocks->mapWithKeys(function ($stock) {
                return [
                    $stock->product_id => [
                        'product_name' => $stock->product->name,
                        'stock' => $stock->stock
                    ]
                ];
            });

            return new WarehouseStockDTO(
                id: $warehouse->id,
                name: $warehouse->name,
                products: $products->toArray()
            );
        });
    }
}