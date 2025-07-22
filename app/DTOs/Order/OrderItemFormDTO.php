<?php

namespace App\DTOs\Order;

final readonly class OrderItemFormDTO
{
    public function __construct(
        public int $product_id,
        public int $count
    )
    {
    }
}