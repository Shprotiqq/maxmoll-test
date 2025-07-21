<?php

namespace App\DTOs\Order;

final readonly class CreateOrderItemDTO
{
    public function __construct(
        public int $order_id,
        public int $product_id,
        public int $count
    )
    {
    }
}