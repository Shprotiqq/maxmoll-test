<?php

namespace App\Repositories\StockMovement;

use App\DTOs\StockMovement\ListStockMovementDTO;
use App\Models\StockMovement;
use App\Repositories\Interfaces\StockMovement\StockMovementRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class StockMovementRepository implements StockMovementRepositoryInterface
{
    public function getStockMovements(ListStockMovementDTO $dto): LengthAwarePaginator
    {
        $query = StockMovement::query()
            ->with([
                'warehouse:name',
                'product:name'
            ]);

        $this->applyFilters($query, $dto);

        return $query->paginate($dto->per_page);
    }

    private function applyFilters(Builder $query, ListStockMovementDTO $dto): void
    {
        if ($dto->warehouse_id !== null) {
            $query->where('warehouse_id', $dto->warehouse_id);
        }

        if ($dto->product_id !== null) {
            $query->where('product_id', $dto->product_id);
        }

        if ($dto->date_from !== null) {
            $query->whereDate('created_at', '>=', $dto->date_from);
        }
    }

    public function createStockMovement(
        int $product_id,
        int $warehouse_id,
        int $stock_before,
        int $stock_after,
        string $operation
    ): StockMovement {
        return StockMovement::query()->create([
            'product_id' => $product_id,
            'warehouse_id' => $warehouse_id,
            'stock_before' => $stock_before,
            'stock_after' => $stock_after,
            'operation' => $operation,
            'created_at' => now(),
        ]);
    }
}