<?php

namespace App\Repositories\StockMovement;

use App\DTOs\StockMovement\ListStockMovementDTO;
use App\Models\StockMovement;
use App\Repositories\Interfaces\StockMovement\StockMovementRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Класс StockMovementRepository
 *
 * Репозиторий для работы с историей движений товаров, реализующий методы для получения списка движений и их создания.
 */
class StockMovementRepository implements StockMovementRepositoryInterface
{
    /**
     * Получает список движений товаров с учетом фильтров и пагинации.
     *
     * @param ListStockMovementDTO $dto Объект с данными фильтрации и пагинации.
     * @return LengthAwarePaginator Пагинированный список движений товаров с подгруженными связями.
     */
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

    /**
     * Применяет фильтры к запросу движений товаров.
     *
     * @param Builder $query Построитель запросов для модели StockMovement.
     * @param ListStockMovementDTO $dto Объект с данными фильтрации.
     */
    private function applyFilters(Builder $query, ListStockMovementDTO $dto): void
    {
        // Фильтрация по идентификатору склада, если указано
        if ($dto->warehouse_id !== null) {
            $query->where('warehouse_id', $dto->warehouse_id);
        }

        // Фильтрация по идентификатору товара, если указано
        if ($dto->product_id !== null) {
            $query->where('product_id', $dto->product_id);
        }

        // Фильтрация по дате создания (начало периода), если указано
        if ($dto->date_from !== null) {
            $query->whereDate('created_at', '>=', $dto->date_from);
        }
    }

    /**
     * Создает запись о движении товара на складе.
     *
     * @param int $product_id Идентификатор товара.
     * @param int $warehouse_id Идентификатор склада.
     * @param int $stock_before Остаток товара до изменения.
     * @param int $stock_after Остаток товара после изменения.
     * @param string $operation Тип операции (например, INCREMENT или DECREMENT).
     * @return StockMovement Созданная модель движения товара.
     */
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