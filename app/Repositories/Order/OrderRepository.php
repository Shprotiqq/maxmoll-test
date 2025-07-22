<?php

namespace App\Repositories\Order;

use App\DTOs\Order\CancelOrderDTO;
use App\DTOs\Order\CompleteOrderDTO;
use App\DTOs\Order\CreateOrderDTO;
use App\DTOs\Order\CreateOrderItemDTO;
use App\DTOs\Order\OrderFilterDTO;
use App\DTOs\Order\ResumeOrderDTO;
use App\DTOs\Order\UpdateOrderDTO;
use App\DTOs\Stock\ChangeStockDTO;
use App\DTOs\Stock\GetStockDTO;
use App\Enums\OrderStatus;
use App\Enums\StockOperationEnum;
use App\Exceptions\InvalidChangeStockOperationException;
use App\Exceptions\NegativeCostException;
use App\Exceptions\OrderCancelException;
use App\Exceptions\OrderCompleteException;
use App\Exceptions\OrderResumeException;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Stock;
use App\Repositories\Interfaces;
use App\Repositories\Interfaces\StockMovement\StockMovementRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class OrderRepository implements Interfaces\Order\OrderRepositoryInterface
{
    public function __construct(
        private StockMovementRepositoryInterface $stockMovementRepository,
    )
    {
    }

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

    public function updateOrder(UpdateOrderDTO $dto): Order
    {
        $order = Order::query()
            ->where('id', $dto->order_id)
            ->firstOrFail();

        $order->update([
            'customer' => $dto->customer,
        ]);

        $this->updateOrderItems($order, $dto->items);

        return $order->fresh(['items.product']);
    }

    private function updateOrderItems(Order $order, array $items): void
    {
        $currentItems = $order->items->keyBy('product_id')->toArray();
        $newItems = collect($items)->keyBy('product_id')->toArray();

        foreach ($currentItems as $productId => $currentItem) {
            if (!isset($newItems[$productId])) {
                $stock = $this->getStockForUpdate(
                    new GetStockDTO(
                        warehouse_id: $order->warehouse_id,
                        product_id: $productId
                    )
                );

                $this->changeStockCount(
                    new ChangeStockDTO(
                        stockOperation: StockOperationEnum::INCREMENT,
                        quantity: $currentItem['count'],
                        stock: $stock
                    )
                );

                OrderItem::query()
                    ->where('id', $productId)
                    ->delete();
            }
        }

        foreach ($newItems as $productId => $item) {
            $stock = $this->getStockForUpdate(
                new GetStockDTO(
                    warehouse_id: $order->warehouse_id,
                    product_id: $item->product_id,
                )
            );

            if (isset($currentItems[$productId])) {
                $quantityDiff = $item->count - $currentItems[$productId]['count'];

                if ($quantityDiff != 0) {
                    $operation = $quantityDiff > 0
                        ? StockOperationEnum::DECREMENT
                        : StockOperationEnum::INCREMENT;

                    $this->changeStockCount(
                        new ChangeStockDTO(
                            stockOperation: $operation,
                            quantity: abs($quantityDiff),
                            stock: $stock
                        )
                    );

                    OrderItem::query()
                        ->where('id', $currentItems[$productId]['id'])
                        ->update([
                            'count' => $item->count,
                        ]);
                }
            } else {
                $this->changeStockCount(
                    new ChangeStockDTO(
                        stockOperation: StockOperationEnum::DECREMENT,
                        quantity: $item->count,
                        stock: $stock
                    )
                );

                $this->createOrderItem(
                    new CreateOrderItemDTO(
                        order_id: $order->id,
                        product_id: $item->product_id,
                        count: $item->count,
                    )
                );
            }
        }
    }

    public function changeStockCount(ChangeStockDTO $dto): void
    {
        $stock_before = $dto->stock->stock;

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

        $stock_after = $dto->stock->stock;

        $this->stockMovementRepository->createStockMovement(
            product_id: $dto->stock->product_id,
            warehouse_id: $dto->stock->warehouse_id,
            stock_before: $stock_before,
            stock_after: $stock_after,
            operation: $dto->stockOperation->value,
        );
    }

    public function createOrderItem(CreateOrderItemDTO $dto): OrderItem
    {
        return OrderItem::query()->create([
            'order_id' => $dto->order_id,
            'product_id' => $dto->product_id,
            'count' => $dto->count,
        ]);
    }

    public function completeOrder(CompleteOrderDTO $dto): Order
    {
        $order = Order::query()
            ->where('id', $dto->order_id)
            ->firstOrFail();

        if ($order->status !== OrderStatus::ACTIVE->value) {
            throw new OrderCompleteException('Можно завершить только активный заказ');
        }

        $order->update([
            'status',
            OrderStatus::COMPLETED->value,
        ]);

        return $order;
    }

    public function cancelOrder(CancelOrderDTO $dto): Order
    {
        $order = Order::query()
            ->where('id', $dto->order_id)
            ->with('items')
            ->firstOrFail();

        if ($order->status === OrderStatus::CANCELLED->value) {
            throw new OrderCancelException('Заказ уже отменен');
        }

        if ($order->status === OrderStatus::COMPLETED->value) {
            throw new OrderCancelException('Нельзя отменить завершенный заказ');
        }

        $order->update(['status' => OrderStatus::CANCELLED->value]);

        foreach ($order->items as $item) {
            $stock = $this->getStockForUpdate(
                new GetStockDTO(
                    warehouse_id: $order->warehouse_id,
                    product_id: $item->product_id,
                )
            );

            $this->changeStockCount(
                new ChangeStockDTO(
                    stockOperation: StockOperationEnum::INCREMENT,
                    quantity: $item->count,
                    stock: $stock
                )
            );
        }

        return $order->fresh(['items.product']);
    }

    public function resumeOrder(ResumeOrderDTO $dto): Order
    {
        $order = Order::query()
            ->where('id', $dto->order_id)
            ->with('items')
            ->firstOrFail();

        if ($order->status !== OrderStatus::CANCELLED->value) {
            throw new OrderResumeException('Заказ не отменен');
        }

        foreach ($order->items as $item) {
            $stock = $this->getStock(
                new GetStockDTO(
                    warehouse_id: $order->warehouse_id,
                    product_id: $item->product_id,
                )
            );

            if ($stock->stock < $item->count) {
                throw new NegativeCostException('Недостаточно товара для возобновления заказа');
            }
        }

        foreach ($order->items as $item) {
            $stock = $this->getStockForUpdate(
                new GetStockDTO(
                    warehouse_id: $order->warehouse_id,
                    product_id: $item->product_id,
                )
            );

            $this->changeStockCount(
                new ChangeStockDTO(
                    stockOperation: StockOperationEnum::DECREMENT,
                    quantity: $item->count,
                    stock: $stock
                )
            );
        }

        $order->update(['status' => OrderStatus::ACTIVE->value]);

        return $order->fresh(['items.product']);
    }

    private function getStock(GetStockDTO $dto): Stock
    {
        return Stock::query()
            ->where('warehouse_id', $dto->warehouse_id)
            ->where('product_id', $dto->product_id)
            ->firstOrFail();
    }

    public function getStockForUpdate(GetStockDTO $dto): Stock
    {
        return Stock::query()
            ->where('warehouse_id', $dto->warehouse_id)
            ->where('product_id', $dto->product_id)
            ->lockForUpdate()
            ->firstOrFail();
    }
}
