<?php

namespace App\DTOs\Product;

/**
 * Класс ProductStockDTO
 *
 * DTO для передачи данных о продукте и его остатках на складах.
 */
final readonly class ProductStockDTO
{
    /**
     * @param int $id Идентификатор продукта.
     * @param string $name Название продукта.
     * @param float $price Цена продукта.
     * @param array $stocks Массив данных об остатках продукта на складах.
     */
    public function __construct(
        public int $id,
        public string $name,
        public float $price,
        public array $stocks
    ) {
    }
}