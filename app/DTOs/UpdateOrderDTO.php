<?php

namespace App\DTOs;

class UpdateOrderDTO
{
    public function __construct(
        public ?string $customer,
        public ?int $warehouse_id,
        public ?array $items,
    )
    {
    }
}