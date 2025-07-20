<?php

namespace App\Services\Product;

use App\Contracts\Product\ProductServiceInterface;
use App\Repositories\Interfaces\Product\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

final class ProductService implements ProductServiceInterface
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    )
    {
    }

    public function getProductsWithStocks(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        $perPage = max(1, min(100, $perPage));

        return $this->productRepository->getProductsWithStocks($perPage, $filters);
    }
}