<?php

namespace App\Repositories\Order;

use App\DTOs\ChangeStockDTO;
use App\DTOs\GetStockDTO;
use App\DTOs\Order\CreateOrderDTO;
use App\DTOs\Order\CreateOrderItemDTO;
use App\DTOs\Order\OrderFilterDTO;
use App\Enums\OrderStatus;
use App\Enums\StockOperationEnum;
use App\Exceptions\InvalidChangeStockOperationException;
use App\Exceptions\NegativeCostException;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Stock;
use App\Repositories\Interfaces;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

final class OrderRepository implements Interfaces\Order\OrderRepositoryInterface
{

    public function getOrderWithFilters(OrderFilterDTO $dto): LengthAwarePaginator
    {
        $query = Order::query()
            ->with([
                'warehouse:name',
                'items.product:id,name,price'
            ]);

        $this->applyFilters($query, $dto);

        return $query->paginate($dto->per_page);
    }

    private function applyFilters(Builder $query, OrderFilterDTO $dto): void
    {
        if ($dto->customer) {
            $query->where('customer', 'like', '%' . $dto->customer . '%');
        }

        if ($dto->status) {
            $query->where('status', $dto->status);
        }

        if ($dto->warehouse) {
            $query->whereHas('warehouse_id', function ($q) use ($dto) {
                $q->where('name', 'like', '%' . $dto->warehouse . '%');
            });
        }

        if ($dto->date_from) {
            $query->where('created_at', '>=', $dto->date_from);
        }

        if ($dto->date_to) {
            $query->where('created_at', '<=', $dto->date_from);
        }
    }


    public function createOrder(CreateOrderDTO $dto): Order
    {
        return Order::query()->create([
            'customer' => $dto->customer,
            'warehouse_id' => $dto->warehouse_id,
            'status' => OrderStatus::ACTIVE->value,
            'created_at' => now(),
        ]);
    }

    public function changeStockCount(ChangeStockDTO $dto): void
    {
        if ($dto->stockOperation === StockOperationEnum::INCREMENT) {
            $stock = $dto->stock->stock + $dto->quantity;
        } elseif ($dto->stockOperation === StockOperationEnum::DECREMENT) {
            $stock = $dto->stock->stock - $dto->quantity;
        } else {
            throw new InvalidChangeStockOperationException(
                "Операция изменения остатков не поддерживается {$dto->stockOperation->value}"
            );
        }

        if ($stock < 0) {
            throw new NegativeCostException('Нет товаров на складе');
        }

        $dto->stock->update(['stock' => $stock]);
    }

    public function getStock(GetStockDTO $dto): Stock
    {
        return Stock::query()
            ->where('warehouse_id', $dto->warehouse_id)
            ->where('product_id', $dto->product_id)
            ->lockForUpdate()
            ->firstOrFail();
    }

    public function createOrderItem(CreateOrderItemDTO $dto): OrderItem
    {
        return OrderItem::query()->create([
            'order_id' => $dto->order_id,
            'product_id' => $dto->product_id,
            'count' => $dto->count,
        ]);
    }
}