<?php

namespace App\DTOs\StockMovement;

final readonly class ListStockMovementDTO
{
    public function __construct(
        public ?int $product_id = null,
        public ?int $warehouse_id = null,
        public ?string $date_from = null,
        public int $per_page = 10
    )
    {
    }
}