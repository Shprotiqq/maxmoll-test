<?php

namespace App\Repositories\Product;

use App\DTOs\FiltersDTO;
use App\DTOs\Product\ProductStockDTO;
use App\Models\Product;
use App\Models\Stock;
use App\Repositories\Interfaces\Product\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Класс ProductRepository
 *
 * Репозиторий для работы с товарами, реализующий методы для получения списка товаров с остатками на складах.
 */
final class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Получает список товаров с остатками на складах с учетом фильтров и пагинации.
     *
     * @param FiltersDTO $dto Объект с данными фильтрации и пагинации.
     * @return LengthAwarePaginator Пагинированный список товаров в формате ProductStockDTO.
     */
    public function getProductsWithStocks(FiltersDTO $dto): LengthAwarePaginator
    {
        $query = Product::query()
            ->with(['stocks:product_id,warehouse_id,stock']);

        $this->applyFilters($query, $dto);

        $products = $query->paginate($dto->per_page);

        $products->setCollection(
            $this->transformToProductStockDTO($products->getCollection())
        );

        return $products;
    }

    /**
     * Применяет фильтры к запросу товаров.
     *
     * @param Builder $query Построитель запросов для модели Product.
     * @param FiltersDTO $dto Объект с данными фильтрации.
     */
    private function applyFilters(Builder $query, FiltersDTO $dto): void
    {
        // Фильтрация по имени товара, если указано
        if (!empty($dto->filters['name'])) {
            $query->where('name', 'like', '%' . $dto->filters['name'] . '%');
        }
    }

    /**
     * Преобразует коллекцию товаров в коллекцию DTO с информацией об остатках.
     *
     * @param Collection $products Коллекция моделей товаров.
     * @return Collection Коллекция объектов ProductStockDTO.
     */
    private function transformToProductStockDTO(Collection $products): Collection
    {
        return $products->map(function (Product $product) {
            $stocks = $product->stocks->mapWithKeys(function (Stock $stock) {
                return [
                    $stock->warehouse_id => [
                        'warehouse_name' => $stock->warehouse->name,
                        'stock' => $stock->stock
                    ]
                ];
            });

            return new ProductStockDTO(
                id: $product->id,
                name: $product->name,
                price: $product->price,
                stocks: $stocks->toArray()
            );
        });
    }
}