<?php

namespace App\DTOs\Order;

final readonly class OrderDTO
{
    public function __construct(
        public int $id,
        public string $customer,
        public string $created_at,
        public ?string $completed_at,
        public int $warehouse_id,
        public string $warehouse_name,
        public string $status,
        public array $items
    )
    {
    }
}