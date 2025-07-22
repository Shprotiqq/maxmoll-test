<?php

namespace App\Contracts\StockMovement;

use App\DTOs\StockMovement\ListStockMovementDTO;
use Illuminate\Pagination\LengthAwarePaginator;

interface StockMovementServiceInterface
{
    public function getStockMovements(ListStockMovementDTO $dto): LengthAwarePaginator;
}