<?php

namespace App\Repositories\Interfaces\Order;

use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface
{
    public function getOrderWithFilters(int $perPage = 10, array $filters = []): LengthAwarePaginator;
    public function findById(int $orderId): Order;
    public function save(Order $order): Order;
    public function deleteItems(Order $order): void;
}