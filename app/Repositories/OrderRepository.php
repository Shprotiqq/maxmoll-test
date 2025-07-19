<?php

namespace App\Repositories;

use App\DTOs\OrderDTO;
use App\DTOs\OrderItemDTO;
use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository implements Interfaces\OrderRepositoryInterface
{

    public function getOrderWithFilters(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        $query = Order::with([
           'warehouse:id,name',
           'items.product:id,name,price'
        ]);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['customer'])) {
            $query->where('customer', 'like', '%' . $filters['customer'] . '%');
        }

        if (!empty($filters['warehouse_id'])) {
            $query->where('warehouse_id', $filters['warehouse_id']);
        }

        if (!empty($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        $orders = $query->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $orders->getCollection()->transform(function ($order) {
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
        });

        return $orders;
    }
}