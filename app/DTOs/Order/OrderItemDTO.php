<?php

namespace App\DTOs\Order;

final readonly class OrderItemDTO
{
    public function __construct(
        public int $product_id,
        public string $product_name,
        public float $product_price,
        public int $count
    )
    {
    }
}