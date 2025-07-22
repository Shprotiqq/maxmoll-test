<?php

namespace App\DTOs\Order;

/**
 * Класс ResumeOrderDTO
 *
 * DTO для передачи данных, необходимых для возобновления отменённого заказа.
 */
final readonly class ResumeOrderDTO
{
    /**
     * @param int $order_id Идентификатор заказа, который необходимо возобновить.
     */
    public function __construct(
        public int $order_id
    ) {
    }
}