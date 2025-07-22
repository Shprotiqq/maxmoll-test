<?php

namespace App\Repositories\Interfaces\Order;

use App\DTOs\Order\CancelOrderDTO;
use App\DTOs\Order\CompleteOrderDTO;
use App\DTOs\Order\CreateOrderDTO;
use App\DTOs\Order\CreateOrderItemDTO;
use App\DTOs\Order\OrderFilterDTO;
use App\DTOs\Order\ResumeOrderDTO;
use App\DTOs\Order\UpdateOrderDTO;
use App\DTOs\Stock\GetStockDTO;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Stock;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface
{
    public function getOrderWithFilters(OrderFilterDTO $dto): LengthAwarePaginator;

    public function createOrder(CreateOrderDTO $dto): Order;

    public function updateOrder(UpdateOrderDTO $dto): Order;

    public function createOrderItem(CreateOrderItemDTO $dto): OrderItem;

    public function completeOrder(CompleteOrderDTO $dto): Order;

    public function cancelOrder(CancelOrderDTO $dto): Order;

    public function resumeOrder(ResumeOrderDTO $dto): Order;

    public function getStockForUpdate(GetStockDTO $dto): Stock;
}
