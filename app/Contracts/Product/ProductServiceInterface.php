<?php

namespace App\Contracts\Product;

use App\DTOs\FiltersDTO;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductServiceInterface
{
    public function getProductsWithStocks(FiltersDTO $dto): LengthAwarePaginator;
}