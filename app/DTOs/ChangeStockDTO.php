<?php

namespace App\DTOs;

use App\Enums\StockOperationEnum;
use App\Models\Stock;

final class ChangeStockDTO
{
    public function __construct(
        public StockOperationEnum $stockOperation,
        public int $quantity,
        public Stock $stock
    )
    {
    }
}