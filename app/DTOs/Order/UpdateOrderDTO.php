<?php

namespace App\DTOs\Order;

final readonly class UpdateOrderDTO
{
    public function __construct(
        public int $order_id,
        public string $customer,
        public array $items
    )
    {
    }
}
