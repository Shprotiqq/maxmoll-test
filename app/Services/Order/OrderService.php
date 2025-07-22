<?php

namespace App\Services\Order;

use App\Contracts\Order\OrderServiceInterface;
use App\DTOs\ChangeStockDTO;
use App\DTOs\GetStockDTO;
use App\DTOs\Order\CancelOrderDTO;
use App\DTOs\Order\CreateOrderDTO;
use App\DTOs\Order\CreateOrderItemDTO;
use App\DTOs\Order\CompleteOrderDTO;
use App\DTOs\Order\OrderFilterDTO;
use App\DTOs\Order\ResumeOrderDTO;
use App\DTOs\Order\UpdateOrderDTO;
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

final class OrderService implements OrderServiceInterface
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
    )
    {
    }

    public function getOrders(OrderFilterDTO $dto): LengthAwarePaginator
    {
        return $this->orderRepository->getOrderWithFilters($dto);
    }

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
        }
        catch (Throwable $exception) {
            DB::rollBack();
            logger()->error($exception);
            throw new OrderUpdateException('Произошла ошибка при обновлении заказа');
        }
    }

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
        }
        catch (Throwable $exception) {
            DB::rollBack();
            logger()->error($exception);
            throw new OrderCompleteException('Произошла ошибка при завершении заказа');
        }
    }

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
        } catch (Throwable $exception){
            DB::rollBack();
            logger()->error($exception);
            throw new OrderCancelException('Произошла ошибкка при отмене заказа');
        }
    }

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
