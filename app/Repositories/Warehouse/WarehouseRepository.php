<?php

namespace App\Repositories\Warehouse;

use App\DTOs\FiltersDTO;
use App\DTOs\Warehouse\WarehouseStockDTO;
use App\Models\Warehouse;
use App\Repositories\Interfaces;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final class WarehouseRepository implements Interfaces\Warehouse\WarehouseRepositoryInterface
{
    public function getWarehousesWithStockInfo(FiltersDTO $dto): LengthAwarePaginator
    {
        $query = Warehouse::query()
            ->with(['stocks.product:id,name']);

        $this->applyFilters($query, $dto);

        $warehouses = $query->paginate($dto->perPage);

        $warehouses->setCollection(
            $this->transformToWarehouseStockDTO($warehouses->getCollection())
        );

        return $warehouses;
    }

    private function applyFilters(Builder $query, FiltersDTO $dto): void
    {
        if (!empty($dto->filters['name'])) {
            $query->where('name', 'like', '%' . $dto->filters['name'] . '%');
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