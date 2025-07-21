<?php

namespace App\Services\Order;

use App\Contracts\Order\OrderServiceInterface;
use App\DTOs\ChangeStockDTO;
use App\DTOs\GetStockDTO;
use App\DTOs\Order\CreateOrderDTO;
use App\DTOs\Order\CreateOrderItemDTO;
use App\DTOs\Order\OrderFilterDTO;
use App\DTOs\Order\OrderItemDTO;
use App\Enums\StockOperationEnum;
use App\Exceptions\NegativeCostException;
use App\Exceptions\OrderCreationException;
use App\Models\Order;
use App\Repositories\Interfaces\Order\OrderRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Throwable;

final class OrderService implements OrderServiceInterface
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
    ) {
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
                $stock = $this->orderRepository->getStock(
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
}