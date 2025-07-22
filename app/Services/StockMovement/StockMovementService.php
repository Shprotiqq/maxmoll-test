<?php

namespace App\Services\StockMovement;

use App\Contracts\StockMovement\StockMovementServiceInterface;
use App\DTOs\StockMovement\ListStockMovementDTO;
use App\Repositories\Interfaces\StockMovement\StockMovementRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class StockMovementService implements StockMovementServiceInterface
{
    public function __construct(
        private StockMovementRepositoryInterface $stockMovementRepository,
    )
    {
    }

    public function getStockMovements(ListStockMovementDTO $dto): LengthAwarePaginator
    {
        return $this->stockMovementRepository->getStockMovements($dto);
    }
}