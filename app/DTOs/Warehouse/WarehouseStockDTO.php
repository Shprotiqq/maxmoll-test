<?php

namespace App\DTOs\Warehouse;

/**
 * Класс WarehouseStockDTO
 *
 * DTO для передачи данных о складе и его товарных остатках.
 */
final readonly class WarehouseStockDTO
{
    /**
     * @param int $id Идентификатор склада.
     * @param string $name Название склада.
     * @param array $products Массив товаров на складе, содержащий данные о продуктах и их остатках.
     */
    public function __construct(
        public int $id,
        public string $name,
        public array $products
    ) {
    }
}