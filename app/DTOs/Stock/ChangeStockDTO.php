<?php

namespace App\DTOs\Stock;

use App\Enums\StockOperationEnum;
use App\Models\Stock;

/**
 * Класс ChangeStockDTO
 *
 * DTO для передачи данных, необходимых для изменения остатка товара на складе.
 */
final readonly class ChangeStockDTO
{
    /**
     * @param StockOperationEnum $stockOperation Тип операции с остатком (INCREMENT или DECREMENT).
     * @param int $quantity Количество единиц товара для изменения остатка.
     * @param Stock $stock Модель остатка, содержащая информацию о складе, продукте и остатке товара.
     */
    public function __construct(
        public StockOperationEnum $stockOperation,
        public int $quantity,
        public Stock $stock
    ) {
    }
}