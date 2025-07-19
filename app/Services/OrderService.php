<?php

namespace App\Services;

use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderService
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
    )
    {
    }

    public function getOrders(int $perPage = 10, array $filter = []): LengthAwarePaginator
    {
        $perPage = max(1, min(100, $perPage));

        return $this->orderRepository->getOrderWithFilters($perPage, $filter);
    }
}