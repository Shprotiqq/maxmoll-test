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

/**
 * Интерфейс OrderRepositoryInterface
 *
 * Определяет контракт для работы с заказами, включая методы для создания, обновления, фильтрации и управления статусами заказов.
 */
interface OrderRepositoryInterface
{
    /**
     * Получает список заказов с учетом фильтров и пагинации.
     *
     * @param OrderFilterDTO $dto Объект с данными фильтрации и пагинации.
     * @return LengthAwarePaginator Пагинированный список заказов с подгруженными связями.
     */
    public function getOrderWithFilters(OrderFilterDTO $dto): LengthAwarePaginator;

    /**
     * Создает новый заказ.
     *
     * @param CreateOrderDTO $dto Объект с данными для создания заказа.
     * @return Order Созданная модель заказа.
     */
    public function createOrder(CreateOrderDTO $dto): Order;

    /**
     * Обновляет существующий заказ и его позиции.
     *
     * @param UpdateOrderDTO $dto Объект с данными для обновления заказа.
     * @return Order Обновленная модель заказа с подгруженными позициями.
     */
    public function updateOrder(UpdateOrderDTO $dto): Order;

    /**
     * Создает новую позицию заказа.
     *
     * @param CreateOrderItemDTO $dto Объект с данными для создания позиции заказа.
     * @return OrderItem Созданная модель позиции заказа.
     */
    public function createOrderItem(CreateOrderItemDTO $dto): OrderItem;

    /**
     * Завершает заказ, переводя его в статус COMPLETED.
     *
     * @param CompleteOrderDTO $dto Объект с данными для завершения заказа.
     * @return Order Обновленная модель заказа.
     */
    public function completeOrder(CompleteOrderDTO $dto): Order;

    /**
     * Отменяет заказ и возвращает товары на склад.
     *
     * @param CancelOrderDTO $dto Объект с данными для отмены заказа.
     * @return Order Обновленная модель заказа с подгруженными позициями.
     */
    public function cancelOrder(CancelOrderDTO $dto): Order;

    /**
     * Возобновляет отмененный заказ, списывая товары со склада.
     *
     * @param ResumeOrderDTO $dto Объект с данными для возобновления заказа.
     * @return Order Обновленная модель заказа с подгруженными позициями.
     */
    public function resumeOrder(ResumeOrderDTO $dto): Order;

    /**
     * Получает остаток товара на складе с блокировкой для обновления.
     *
     * @param GetStockDTO $dto Объект с данными о складе и товаре.
     * @return Stock Модель остатка товара с блокировкой.
     */
    public function getStockForUpdate(GetStockDTO $dto): Stock;
}