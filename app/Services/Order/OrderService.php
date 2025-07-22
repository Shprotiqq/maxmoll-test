<?php

namespace App\Services\Order;

use App\Contracts\Order\OrderServiceInterface;
use App\DTOs\Order\CancelOrderDTO;
use App\DTOs\Order\CompleteOrderDTO;
use App\DTOs\Order\CreateOrderDTO;
use App\DTOs\Order\CreateOrderItemDTO;
use App\DTOs\Order\OrderFilterDTO;
use App\DTOs\Order\ResumeOrderDTO;
use App\DTOs\Order\UpdateOrderDTO;
use App\DTOs\Stock\ChangeStockDTO;
use App\DTOs\Stock\GetStockDTO;
use App\Enums\StockOperationEnum;
use App\Exceptions\NegativeCostException;
use App\Exceptions\OrderCancelException;
use App\Exceptions\OrderCompleteException;
use App\Exceptions\OrderCreationException;
use App\Exceptions\OrderResumeException;
use App\Exceptions\OrderUpdateException;
use App\Models\Order;
use App\Repositories\Interfaces\Order\OrderRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Сервис класс OrderService
 *
 * Реализует сервис для управления заказами, включая создание, обновление, завершение,
 * отмену и возобновление заказов, а также получение списка заказов с фильтрацией.
 */
final readonly class OrderService implements OrderServiceInterface
{
    /**
     * @param OrderRepositoryInterface $orderRepository Репозиторий для работы с заказами.
     */
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
    ) {
    }

    /**
     * Получает список заказов с применением фильтров и пагинацией.
     *
     * @param OrderFilterDTO $dto Объект DTO с параметрами фильтрации (клиент, статус, склад, даты).
     * @return LengthAwarePaginator Пагинированный список заказов с подгруженными связями.
     */
    public function getOrders(OrderFilterDTO $dto): LengthAwarePaginator
    {
        return $this->orderRepository->getOrderWithFilters($dto);
    }

    /**
     * Создаёт новый заказ и связанные с ним позиции, обновляя остатки на складе.
     *
     * @param CreateOrderDTO $dto Объект DTO с данными для создания заказа (клиент, склад, позиции).
     * @return Order Созданная модель заказа.
     * @throws NegativeCostException Если на складе недостаточно товаров для списания.
     * @throws OrderCreationException Если произошла ошибка при создании заказа.
     */
    public function createOrder(CreateOrderDTO $dto): Order
    {
        try {
            DB::beginTransaction();

            $order = $this->orderRepository->createOrder($dto);

            foreach ($dto->items as $item) {
                $stock = $this->orderRepository->getStockForUpdate(
                    new GetStockDTO(
                        warehouse_id: $dto->warehouse_id,
                        product_id: $item->product_id
                    )
                );

                $changeStockDTO = new ChangeStockDTO(
                    stockOperation: StockOperationEnum::DECREMENT,
                    quantity: $item->count,
                    stock: $stock
                );

                $this->orderRepository->createOrderItem(new CreateOrderItemDTO(
                    order_id: $order->id,
                    product_id: $item->product_id,
                    count: $item->count
                ));

                $this->orderRepository->changeStockCount($changeStockDTO);
            }

            DB::commit();

            return $order;
        } catch (NegativeCostException $exception) {
            DB::rollBack();
            logger()->error($exception);
            throw $exception;
        } catch (Throwable $exception) {
            DB::rollBack();
            logger()->error($exception);
            throw new OrderCreationException('Произошла ошибка при создании заказа');
        }
    }

    /**
     * Обновляет существующий заказ, включая его позиции и, при необходимости, остатки на складе.
     *
     * @param UpdateOrderDTO $dto Объект DTO с данными для обновления заказа (идентификатор, клиент, позиции).
     * @return Order Обновлённая модель заказа.
     * @throws OrderUpdateException Если заказ не найден или обновление невозможно.
     */
    public function updateOrder(UpdateOrderDTO $dto): Order
    {
        try {
            DB::beginTransaction();

            $order = $this->orderRepository->updateOrder($dto);

            DB::commit();

            return $order;
        } catch (OrderUpdateException $exception) {
            DB::rollBack();
            logger()->error($exception);
            throw $exception;
        } catch (Throwable $exception) {
            DB::rollBack();
            logger()->error($exception);
            throw new OrderUpdateException('Произошла ошибка при обновлении заказа');
        }
    }

    /**
     * Завершает заказ, устанавливая статус 'completed' и дату завершения.
     *
     * @param CompleteOrderDTO $dto Объект DTO с идентификатором заказа.
     * @return Order Завершённая модель заказа.
     * @throws OrderCompleteException Если заказ уже завершён или не является активным.
     */
    public function completeOrder(CompleteOrderDTO $dto): Order
    {
        try {
            DB::beginTransaction();

            $order = $this->orderRepository->completeOrder($dto);

            DB::commit();

            return $order;
        } catch (OrderCompleteException $exception) {
            DB::rollBack();
            logger()->error($exception);
            throw $exception;
        } catch (Throwable $exception) {
            DB::rollBack();
            logger()->error($exception);
            throw new OrderCompleteException('Произошла ошибка при завершении заказа');
        }
    }

    /**
     * Отменяет заказ, возвращая товары на склад и устанавливая статус 'canceled'.
     *
     * @param CancelOrderDTO $dto Объект DTO с идентификатором заказа.
     * @return Order Отменённая модель заказа.
     * @throws OrderCancelException Если заказ не найден или отмена невозможна.
     */
    public function cancelOrder(CancelOrderDTO $dto): Order
    {
        try {
            DB::beginTransaction();

            $order = $this->orderRepository->cancelOrder($dto);

            DB::commit();

            return $order;
        } catch (OrderCancelException $exception) {
            DB::rollBack();
            logger()->error($exception);
            throw $exception;
        } catch (Throwable $exception) {
            DB::rollBack();
            logger()->error($exception);
            throw new OrderCancelException('Произошла ошибка при отмене заказа'); // Исправлено: было 'ошибкка'
        }
    }

    /**
     * Возобновляет отменённый заказ, списывая товары со склада и устанавливая статус 'active'.
     *
     * @param ResumeOrderDTO $dto Объект DTO с идентификатором заказа.
     * @return Order Возобновлённая модель заказа.
     * @throws OrderResumeException Если заказ не найден или возобновление невозможно.
     */
    public function resumeOrder(ResumeOrderDTO $dto): Order
    {
        try {
            DB::beginTransaction();

            $order = $this->orderRepository->resumeOrder($dto);

            DB::commit();

            return $order;
        } catch (OrderResumeException $exception) {
            DB::rollBack();
            logger()->error($exception);
            throw $exception;
        } catch (Throwable $exception) {
            DB::rollBack();
            logger()->error($exception);
            throw new OrderResumeException('Произошла ошибка при возобновлении заказа');
        }
    }
}