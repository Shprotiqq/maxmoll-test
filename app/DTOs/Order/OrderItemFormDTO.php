<?php

namespace App\DTOs\Order;

/**
 * Класс OrderItemFormDTO
 *
 * DTO для передачи данных о продукте и его количестве для формирования позиции заказа.
 */
final readonly class OrderItemFormDTO
{
    /**
     * @param int $product_id Идентификатор продукта, добавляемого в заказ.
     * @param int $count Количество единиц продукта в позиции заказа.
     */
    public function __construct(
        public int $product_id,
        public int $count
    ) {
    }
}