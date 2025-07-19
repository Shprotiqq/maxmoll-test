<?php

namespace App\DTOs;

class WarehouseDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
    )
    {
    }
}