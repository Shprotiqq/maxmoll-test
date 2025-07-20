<?php

namespace App\DTOs\Warehouse;

final readonly class WarehouseDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $created_at,
        public string $updated_at,
    ) {
    }
}