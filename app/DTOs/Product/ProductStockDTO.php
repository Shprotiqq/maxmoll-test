<?php

namespace App\DTOs\Product;

final readonly class ProductStockDTO
{
        public function __construct(
            public int $id,
            public string $name,
            public float $price,
            public array $stocks
        )
        {
        }
}