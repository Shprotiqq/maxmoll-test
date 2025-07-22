<?php

namespace App\Repositories\Warehouse;

use App\DTOs\FiltersDTO;
use App\DTOs\Warehouse\WarehouseStockDTO;
use App\Models\Warehouse;
use App\Repositories\Interfaces;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Класс WarehouseRepository
 *
 * Репозиторий для работы со складами, реализующий методы для получения списка складов с информацией об остатках товаров.
 */
final class WarehouseRepository implements Interfaces\Warehouse\WarehouseRepositoryInterface
{
    /**
     * Получает список складов с информацией об остатках товаров, с учетом фильтров и пагинации.
     *
     * @param FiltersDTO $dto Объект с данными фильтрации и пагинации.
     * @return LengthAwarePaginator Пагинированный список складов в формате WarehouseStockDTO.
     */
    public function getWarehousesWithStockInfo(FiltersDTO $dto): LengthAwarePaginator
    {
        $query = Warehouse::query()
            ->with(['stocks.product:id,name']);

        $this->applyFilters($query, $dto);

        $warehouses = $query->paginate($dto->per_page);

        $warehouses->setCollection(
            $this->transformToWarehouseStockDTO($warehouses->getCollection())
        );

        return $warehouses;
    }

    /**
     * Применяет фильтры к запросу складов.
     *
     * @param Builder $query Построитель запросов для модели Warehouse.
     * @param FiltersDTO $dto Объект с данными фильтрации.
     */
    private function applyFilters(Builder $query, FiltersDTO $dto): void
    {
        // Фильтрация по имени склада, если указано
        if (!empty($dto->filters['name'])) {
            $query->where('name', 'like', '%' . $dto->filters['name'] . '%');
        }
    }

    /**
     * Преобразует коллекцию складов в коллекцию DTO с информацией об остатках товаров.
     *
     * @param Collection $warehouses Коллекция моделей складов.
     * @return Collection Коллекция объектов WarehouseStockDTO.
     */
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