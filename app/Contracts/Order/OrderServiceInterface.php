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

/**
 * Интерфейс OrderServiceInterface
 *
 * Определяет контракт для сервиса управления заказами, включая операции получения,
 * создания, обновления, завершения, отмены и возобновления заказов.
 */
interface OrderServiceInterface
{
    /**
     * Получает список заказов с применением фильтров и пагинацией.
     *
     * @param OrderFilterDTO $dto Объект DTO с параметрами фильтрации (например, клиент, статус, склад, даты).
     * @return LengthAwarePaginator Пагинированный список заказов с подгруженными связями.
     */
    public function getOrders(OrderFilterDTO $dto): LengthAwarePaginator;

    /**
     * Создаёт новый заказ и связанные с ним позиции, обновляя остатки на складе.
     *
     * @param CreateOrderDTO $dto Объект DTO с данными для создания заказа (клиент, склад, позиции).
     * @return Order Созданная модель заказа с подгруженными связями.
     * @throws \App\Exceptions\NegativeCostException Если на складе недостаточно товаров.
     * @throws \App\Exceptions\OrderCreationException Если произошла ошибка при создании заказа.
     */
    public function createOrder(CreateOrderDTO $dto): Order;

    /**
     * Обновляет существующий заказ, включая его позиции и, при необходимости, остатки на складе.
     *
     * @param UpdateOrderDTO $dto Объект DTO с данными для обновления заказа (идентификатор, клиент, позиции).
     * @return Order Обновлённая модель заказа с подгруженными связями.
     * @throws \App\Exceptions\OrderUpdateException Если заказ не найден или обновление невозможно.
     */
    public function updateOrder(UpdateOrderDTO $dto): Order;

    /**
     * Завершает заказ, устанавливая статус 'completed' и дату завершения.
     *
     * @param CompleteOrderDTO $dto Объект DTO с идентификатором заказа.
     * @return Order Завершённая модель заказа с подгруженными связями.
     * @throws \App\Exceptions\OrderCompleteException Если заказ уже завершён или не является активным.
     */
    public function completeOrder(CompleteOrderDTO $dto): Order;

    /**
     * Отменяет заказ, возвращая товары на склад и устанавливая статус 'canceled'.
     *
     * @param CancelOrderDTO $dto Объект DTO с идентификатором заказа.
     * @return Order Отменённая модель заказа с подгруженными связями.
     * @throws \App\Exceptions\OrderCancelException Если заказ не найден или отмена невозможна.
     */
    public function cancelOrder(CancelOrderDTO $dto): Order;

    /**
     * Возобновляет отменённый заказ, списывая товары со склада и устанавливая статус 'active'.
     *
     * @param ResumeOrderDTO $dto Объект DTO с идентификатором заказа.
     * @return Order Возобновлённая модель заказа с подгруженными связями.
     * @throws \App\Exceptions\OrderResumeException Если заказ не найден или возобновление невозможно.
     */
    public function resumeOrder(ResumeOrderDTO $dto): Order;
}