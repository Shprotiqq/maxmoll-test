<?php

namespace App\DTOs\Order;

/**
 * Класс CancelOrderDTO
 *
 * DTO для передачи данных, необходимых для отмены заказа.
 */
final readonly class CancelOrderDTO
{
    /**
     * @param int $order_id Идентификатор заказа, который необходимо отменить.
     */
    public function __construct(
        public int $order_id,
    ) {
    }
}