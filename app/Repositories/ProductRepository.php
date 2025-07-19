<?php

namespace App\Repositories;

use App\DTOs\ProductStockDTO;
use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{

    public function getProductsWithStocks(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        $query = Product::with(['stocks:product_id,warehouse, stock']);

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        $products = $query->paginate($perPage);

        $products->getCollection()->transform(function ($product) {
            $stocks = [];
            foreach ($product->stocks as $stock) {
                $stocks[$stock->warehouse->id] = $stock->stock;
            }

            return new ProductStockDTO(
                id: $product->id,
                name: $product->name,
                price: $product->price,
                stocks: $stocks
            );
        });

        return $products;
    }
}