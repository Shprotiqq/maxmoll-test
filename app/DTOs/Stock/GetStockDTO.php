<?php

namespace App\DTOs\Stock;

/**
 * Класс GetStockDTO
 *
 * DTO для передачи данных, необходимых для получения информации об остатке товара на складе.
 */
final readonly class GetStockDTO
{
    /**
     * @param int $warehouse_id Идентификатор склада.
     * @param int $product_id Идентификатор товара.
     */
    public function __construct(
        public int $warehouse_id,
        public int $product_id,
    ) {
    }
}