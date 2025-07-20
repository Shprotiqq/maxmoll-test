<?php

namespace App\Repositories\Product;

use App\DTOs\Product\ProductStockDTO;
use App\Models\Product;
use App\Models\Stock;
use App\Repositories\Interfaces\Product\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final class ProductRepository implements ProductRepositoryInterface
{

    public function getProductsWithStocks(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        $query = Product::with(['stocks:product_id,warehouse_id,stock']);

        $this->applyFilters($query, $filters);

        $products = $query->paginate($perPage);

        $products->setCollection(
            $this->transformToProductStockDTO($products->getCollection())
        );

        return $products;
    }

    private function applyFilters($query, array $filters): void
    {
        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }
    }

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