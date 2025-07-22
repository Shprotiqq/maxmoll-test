<?php

namespace App\Repositories\Interfaces\StockMovement;

use App\DTOs\StockMovement\ListStockMovementDTO;
use App\Models\StockMovement;
use Illuminate\Pagination\LengthAwarePaginator;

interface StockMovementRepositoryInterface
{
    public function getStockMovements(ListStockMovementDTO $dto): LengthAwarePaginator;

    public function createStockMovement(
        int $product_id,
        int $warehouse_id,
        int $stock_before,
        int $stock_after,
        string $operation
    ): StockMovement;
}