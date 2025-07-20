<?php

namespace App\Contracts\Order;

use App\DTOs\Order\CreateOrderDTO;
use App\DTOs\Order\OrderDTO;
use App\DTOs\Order\UpdateOrderDTO;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderServiceInterface
{
    public function getOrders(int $perPage = 10, array $filter = []): LengthAwarePaginator;
    public function createOrder(CreateOrderDTO $dto): OrderDTO;
    public function updateOrder(int $orderId, UpdateOrderDTO $dto): OrderDTO;
    public function completeOrder(int $orderId): OrderDTO;
    public function cancelOrder(int $orderId): OrderDTO;
    public function resumeOrder(int $orderId): OrderDTO;

}