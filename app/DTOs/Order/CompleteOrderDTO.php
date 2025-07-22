<?php

namespace App\DTOs\Order;

/**
 * Класс CompleteOrderDTO
 *
 * DTO для передачи данных, необходимых для завершения заказа.
 */
final readonly class CompleteOrderDTO
{
    /**
     * @param int $order_id Идентификатор заказа, который необходимо завершить.
     */
    public function __construct(
        public int $order_id
    ) {
    }
}