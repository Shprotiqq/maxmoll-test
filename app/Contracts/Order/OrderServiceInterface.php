<?php

namespace App\Contracts\Order;

use App\DTOs\Order\CreateOrderDTO;
use App\DTOs\Order\OrderFilterDTO;
use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderServiceInterface
{
    public function getOrders(OrderFilterDTO $dto): LengthAwarePaginator;

    public function createOrder(CreateOrderDTO $dto): Order;
}