<?php

namespace App\Contracts\Order;

use App\DTOs\Order\CancelOrderDTO;
use App\DTOs\Order\CompleteOrderDTO;
use App\DTOs\Order\CreateOrderDTO;
use App\DTOs\Order\OrderFilterDTO;
use App\DTOs\Order\ResumeOrderDTO;
use App\DTOs\Order\UpdateOrderDTO;
use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderServiceInterface
{
    public function getOrders(OrderFilterDTO $dto): LengthAwarePaginator;

    public function createOrder(CreateOrderDTO $dto): Order;

    public function updateOrder(UpdateOrderDTO $dto): Order;

    public function completeOrder(CompleteOrderDTO $dto): Order;

    public function cancelOrder(CancelOrderDTO $dto): Order;

    public function resumeOrder(ResumeOrderDTO $dto): Order;
}
