<?php

namespace App\DTOs\Order;

/**
 * Класс CreateOrderDTO
 *
 * DTO для передачи данных, необходимых для создания нового заказа.
 */
final readonly class CreateOrderDTO
{
    /**
     * @param string $customer Имя клиента, создающего заказ.
     * @param int $warehouse_id Идентификатор склада, с которого списываются товары.
     * @param array $items Массив элементов заказа, содержащий данные о продуктах и их количестве.
     */
    public function __construct(
        public string $customer,
        public int $warehouse_id,
        public array $items
    ) {
    }
}