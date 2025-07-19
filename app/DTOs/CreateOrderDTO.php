<?php

namespace App\DTOs;

readonly class CreateOrderDTO
{
    public function __construct(
        public string $customer,
        public int $warehouse_id,
        public array $items
    )
    {
    }
}