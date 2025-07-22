<?php

namespace App\DTOs;

final readonly class FiltersDTO
{
    public const DEFAULT_PER_PAGE = 10;

    public function __construct(
        public int $perPage = self::DEFAULT_PER_PAGE,
        public array $filters,
    )
    {
    }
}