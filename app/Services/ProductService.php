<?php

namespace App\Services;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
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