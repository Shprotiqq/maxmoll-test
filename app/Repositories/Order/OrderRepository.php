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

/**
 * Класс OrderRepository
 *
 * Репозиторий для работы с заказами, реализующий методы для создания, обновления, фильтрации и управления статусами заказов.
 */
final readonly class OrderRepository implements Interfaces\Order\OrderRepositoryInterface
{
    /**
     * @param StockMovementRepositoryInterface $stockMovementRepository Репозиторий для записи движений остатков товаров.
     */
    public function __construct(
        private StockMovementRepositoryInterface $stockMovementRepository,
    )
    {
    }

    /**
     * Получает список заказов с учетом фильтров и пагинации.
     *
     * @param OrderFilterDTO $dto Объект с данными фильтрации и пагинации.
     * @return LengthAwarePaginator Пагинированный список заказов с подгруженными связями.
     */
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

    /**
     * Применяет фильтры к запросу заказов.
     *
     * @param Builder $query Построитель запросов для модели Order.
     * @param OrderFilterDTO $dto Объект с данными фильтрации.
     */
    private function applyFilters(Builder $query, OrderFilterDTO $dto): void
    {
        // Фильтрация по имени клиента, если указано
        if ($dto->customer) {
            $query->where('customer', 'like', '%' . $dto->customer . '%');
        }

        // Фильтрация по статусу заказа, если указано
        if ($dto->status) {
            $query->where('status', $dto->status);
        }

        // Фильтрация по имени склада, если указано
        if ($dto->warehouse) {
            $query->whereHas('warehouse_id', function ($q) use ($dto) {
                $q->where('name', 'like', '%' . $dto->warehouse . '%');
            });
        }

        // Фильтрация по дате создания (начало периода), если указано
        if ($dto->date_from) {
            $query->where('created_at', '>=', $dto->date_from);
        }

        // Фильтрация по дате создания (конец периода), если указано
        if ($dto->date_to) {
            $query->where('created_at', '<=', $dto->date_to);
        }
    }

    /**
     * Создает новый заказ.
     *
     * @param CreateOrderDTO $dto Объект с данными для создания заказа.
     * @return Order Созданная модель заказа.
     */
    public function createOrder(CreateOrderDTO $dto): Order
    {
        return Order::query()->create([
            'customer' => $dto->customer,
            'warehouse_id' => $dto->warehouse_id,
            'status' => OrderStatus::ACTIVE->value,
            'created_at' => now(),
        ]);
    }

    /**
     * Обновляет существующий заказ и его позиции.
     *
     * @param UpdateOrderDTO $dto Объект с данными для обновления заказа.
     * @return Order Обновленная модель заказа с подгруженными позициями.
     */
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

    /**
     * Обновляет позиции заказа, синхронизируя остатки на складе.
     *
     * @param Order $order Модель заказа.
     * @param array $items Массив новых позиций заказа.
     */
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

                // Если есть разница, обновляем остатки
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

    /**
     * Изменяет остаток товара на складе и фиксирует движение.
     *
     * @param ChangeStockDTO $dto Объект с данными для изменения остатка.
     * @throws InvalidChangeStockOperationException Если операция не поддерживается.
     * @throws NegativeCostException Если остаток становится отрицательным.
     */
    public function changeStockCount(ChangeStockDTO $dto): void
    {
        $stock_before = $dto->stock->stock;

        // Вычисление нового остатка в зависимости от операции
        if ($dto->stockOperation === StockOperationEnum::INCREMENT) {
            $stock = $dto->stock->stock + $dto->quantity;
        } elseif ($dto->stockOperation === StockOperationEnum::DECREMENT) {
            $stock = $dto->stock->stock - $dto->quantity;
        } else {
            throw new InvalidChangeStockOperationException(
                "Операция изменения остатков не поддерживается {$dto->stockOperation->value}"
            );
        }

        // Проверка на отрицательный остаток
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

    /**
     * Создает новую позицию заказа.
     *
     * @param CreateOrderItemDTO $dto Объект с данными для создания позиции заказа.
     * @return OrderItem Созданная модель позиции заказа.
     */
    public function createOrderItem(CreateOrderItemDTO $dto): OrderItem
    {
        return OrderItem::query()->create([
            'order_id' => $dto->order_id,
            'product_id' => $dto->product_id,
            'count' => $dto->count,
        ]);
    }

    /**
     * Завершает заказ, переводя его в статус COMPLETED.
     *
     * @param CompleteOrderDTO $dto Объект с данными для завершения заказа.
     * @return Order Обновленная модель заказа.
     * @throws OrderCompleteException Если заказ не активен.
     */
    public function completeOrder(CompleteOrderDTO $dto): Order
    {
        $order = Order::query()
            ->where('id', $dto->order_id)
            ->firstOrFail();

        // Проверка, что заказ активен
        if ($order->status !== OrderStatus::ACTIVE->value) {
            throw new OrderCompleteException('Можно завершить только активный заказ');
        }

        $order->update([
            'status' => OrderStatus::COMPLETED->value,
        ]);

        return $order;
    }

    /**
     * Отменяет заказ и возвращает товары на склад.
     *
     * @param CancelOrderDTO $dto Объект с данными для отмены заказа.
     * @return Order Обновленная модель заказа с подгруженными позициями.
     * @throws OrderCancelException Если заказ уже отменен или завершен.
     */
    public function cancelOrder(CancelOrderDTO $dto): Order
    {
        $order = Order::query()
            ->where('id', $dto->order_id)
            ->with('items')
            ->firstOrFail();

        // Проверка, что заказ не отменен
        if ($order->status === OrderStatus::CANCELLED->value) {
            throw new OrderCancelException('Заказ уже отменен');
        }

        // Проверка, что заказ не завершен
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

    /**
     * Возобновляет отмененный заказ, списывая товары со склада.
     *
     * @param ResumeOrderDTO $dto Объект с данными для возобновления заказа.
     * @return Order Обновленная модель заказа с подгруженными позициями.
     * @throws OrderResumeException Если заказ не отменен.
     * @throws NegativeCostException Если недостаточно товаров на складе.
     */
    public function resumeOrder(ResumeOrderDTO $dto): Order
    {
        $order = Order::query()
            ->where('id', $dto->order_id)
            ->with('items')
            ->firstOrFail();

        // Проверка, что заказ отменен
        if ($order->status !== OrderStatus::CANCELLED->value) {
            throw new OrderResumeException('Заказ не отменен');
        }

        // Проверка наличия достаточного количества товаров
        foreach ($order->items as $item) {
            $stock = $this->getStock(
                new GetStockDTO(
                    warehouse_id: $order->warehouse_id,
                    product_id: $item->product_id,
                )
            );

            // Если товара недостаточно, выбрасываем NegativeCostException
            if ($stock->stock < $item->count) {
                throw new NegativeCostException('Недостаточно товара для возобновления заказа');
            }
        }

        // Списание товаров со склада для каждой позиции
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

    /**
     * Получает остаток товара на складе.
     *
     * @param GetStockDTO $dto Объект с данными о складе и товаре.
     * @return Stock Модель остатка товара.
     */
    private function getStock(GetStockDTO $dto): Stock
    {
        return Stock::query()
            ->where('warehouse_id', $dto->warehouse_id)
            ->where('product_id', $dto->product_id)
            ->firstOrFail();
    }

    /**
     * Получает остаток товара на складе с блокировкой для обновления.
     *
     * @param GetStockDTO $dto Объект с данными о складе и товаре.
     * @return Stock Модель остатка товара с блокировкой.
     */
    public function getStockForUpdate(GetStockDTO $dto): Stock
    {
        return Stock::query()
            ->where('warehouse_id', $dto->warehouse_id)
            ->where('product_id', $dto->product_id)
            ->lockForUpdate()
            ->firstOrFail();
    }
}