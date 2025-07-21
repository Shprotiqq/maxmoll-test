<?php

namespace App\Repositories\Interfaces\Order;

use App\DTOs\Order\CreateOrderDTO;
use App\DTOs\Order\OrderFilterDTO;
use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface
{
    public function getOrderWithFilters(OrderFilterDTO $dto): LengthAwarePaginator;

    public function createOrder(CreateOrderDTO $dto): Order;
}