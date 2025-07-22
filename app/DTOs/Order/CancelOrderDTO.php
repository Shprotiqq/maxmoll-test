<?php

namespace App\DTOs\Order;

final readonly class CancelOrderDTO
{
    public function __construct(
        public int $order_id,
    )
    {
    }
}
