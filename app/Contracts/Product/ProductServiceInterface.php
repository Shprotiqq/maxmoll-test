<?php

namespace App\Contracts\Product;

use Illuminate\Pagination\LengthAwarePaginator;

interface ProductServiceInterface
{
    public function getProductsWithStocks(int $perPage = 10, array $filters = []): LengthAwarePaginator;
}