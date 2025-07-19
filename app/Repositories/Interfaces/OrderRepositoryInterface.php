<?php

namespace App\Repositories\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface
{
    public function getOrderWithFilters(int $perPage = 10, array $filters = []): LengthAwarePaginator;
}