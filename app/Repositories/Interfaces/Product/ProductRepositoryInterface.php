<?php

namespace App\Repositories\Interfaces\Product;

use App\DTOs\FiltersDTO;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function getProductsWithStocks(FiltersDTO $dto): LengthAwarePaginator;
}