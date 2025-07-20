<?php

namespace App\Repositories\Interfaces\Product;

use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function getProductsWithStocks(int $perPage = 10, array $filters = []): LengthAwarePaginator;
}