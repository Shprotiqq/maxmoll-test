<?php

namespace App\DTOs\Order;

/**
 * Класс UpdateOrderDTO
 *
 * DTO для передачи данных, необходимых для обновления существующего заказа.
 */
final readonly class UpdateOrderDTO
{
    /**
     * @param int $order_id Идентификатор заказа, который необходимо обновить.
     * @param string $customer Имя или идентификатор клиента, обновляемого в заказе.
     * @param array $items Массив элементов заказа, содержащий данные о продуктах и их количестве для обновления.
     */
    public function __construct(
        public int $order_id,
        public string $customer,
        public array $items
    ) {
    }
}