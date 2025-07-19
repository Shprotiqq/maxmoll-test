<?php

namespace App\Services;

use App\DTOs\CreateOrderDTO;
use App\DTOs\OrderDTO;
use App\DTOs\OrderItemDTO;
use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Stock;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

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

    public function createOrder(CreateOrderDTO $dto): OrderDTO
    {
        return DB::transaction(function () use ($dto) {
           $order = Order::query()->create([
               'customer' => $dto->customer,
               'warehouse_id' => $dto->warehouse_id,
               'status' => OrderStatus::ACTIVE->value,
               'created_at' => now(),
           ]);

           foreach ($dto->items as $item) {
               $this->addOrderItem($order, $item['product_id'], $item['count']);
           }

           return $this->orderToDTO($order);
        });
    }

    private function addOrderItem(Order $order, int $productId, int $count): void
    {
        $stock = Stock::query()
            ->where('product_id', $productId)
            ->where('warehouse_id', $order->warehouse_id)
            ->firstOrFail();

        if ($stock->count < $count) {
            throw new \Exception('Недостаточно товара {$productId} на складе');
        }

        OrderItem::query()->create([
           'order_id' => $order->id,
           'product_id' => $productId,
           'count' => $count,
        ]);

        $stock->decrement('stock', $count);
    }

    private function orderToDTO(Order $order): OrderDTO
    {
        $order->load(['warehouse', 'items.product']);

        $items = $order->items->map(function ($item) {
            return new OrderItemDTO(
                product_id: $item->product_id,
                product_name: $item->product->name,
                product_price: $item->product->price,
                count: $item->count,
            );
        })->toArray();

        return new OrderDTO(
            id: $order->id,
            customer: $order->customer,
            created_at: $order->created_at->toDateTimeString(),
            completed_at: $order->completed_at?->toDateTimeString(),
            warehouse_id: $order->warehouse_id,
            warehouse_name: $order->warehouse->name,
            status: $order->status,
            items: $items,
        );
    }
}