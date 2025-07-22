<?php

namespace App\DTOs\Order;

final readonly class CompleteOrderDTO
{
    public function __construct(
        public int $order_id
    )
    {
    }
}
