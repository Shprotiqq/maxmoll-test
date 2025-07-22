<?php

namespace App\DTOs\Stock;

final readonly class GetStockDTO
{
    public function __construct(
        public int $warehouse_id,
        public int $product_id,
    )
    {
    }
}