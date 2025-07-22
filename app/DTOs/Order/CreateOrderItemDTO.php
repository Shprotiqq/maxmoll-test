<?php

namespace App\DTOs\Order;

/**
 * Класс CreateOrderItemDTO
 *
 * DTO для передачи данных, необходимых для создания позиции заказа.
 */
final readonly class CreateOrderItemDTO
{
    /**
     * @param int $order_id Идентификатор заказа, к которому относится позиция.
     * @param int $product_id Идентификатор продукта, добавляемого в заказ.
     * @param int $count Количество единиц продукта в позиции заказа.
     */
    public function __construct(
        public int $order_id,
        public int $product_id,
        public int $count
    ) {
    }
}