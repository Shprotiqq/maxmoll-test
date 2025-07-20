<?php

namespace App\DTOs\Warehouse;

final readonly class WarehouseStockDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public array $products
    )
    {
    }
}