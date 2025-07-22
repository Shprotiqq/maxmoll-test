<?php

namespace App\DTOs\Order;

final readonly class OrderFilterDTO
{
    public const DEFAULT_PER_PAGE = 10;

    public function __construct(
        public ?string $customer = null,
        public ?string $status = null,
        public ?string $warehouse = null,
        public ?string $date_from = null,
        public ?string $date_to = null,
        public int $per_page = self::DEFAULT_PER_PAGE,
    )
    {
    }
}