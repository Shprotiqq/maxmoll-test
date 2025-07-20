<?php

namespace App\Services\Product;

use App\Contracts\Product\ProductServiceInterface;
use App\DTOs\FiltersDTO;
use App\Repositories\Interfaces\Product\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class ProductService implements ProductServiceInterface
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    )
    {
    }

    public function getProductsWithStocks(FiltersDTO $dto): LengthAwarePaginator
    {
        return $this->productRepository->getProductsWithStocks($dto);
    }
}