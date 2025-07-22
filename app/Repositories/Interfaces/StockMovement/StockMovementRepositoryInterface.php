<?php

namespace App\Repositories\Interfaces\StockMovement;

use App\DTOs\StockMovement\ListStockMovementDTO;
use App\Models\StockMovement;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Интерфейс StockMovementRepositoryInterface
 *
 * Определяет контракт для работы с историей движений товаров, включая методы для получения списка движений и их создания.
 */
interface StockMovementRepositoryInterface
{
    /**
     * Получает список движений товаров с учетом фильтров и пагинации.
     *
     * @param ListStockMovementDTO $dto Объект с данными фильтрации и пагинации.
     * @return LengthAwarePaginator Пагинированный список движений товаров с подгруженными связями.
     */
    public function getStockMovements(ListStockMovementDTO $dto): LengthAwarePaginator;

    /**
     * Создает запись о движении товара на складе.
     *
     * @param int $product_id Идентификатор товара.
     * @param int $warehouse_id Идентификатор склада.
     * @param int $stock_before Остаток товара до изменения.
     * @param int $stock_after Остаток товара после изменения.
     * @param string $operation Тип операции (INCREMENT или DECREMENT).
     * @return StockMovement Созданная модель движения товара.
     */
    public function createStockMovement(
        int $product_id,
        int $warehouse_id,
        int $stock_before,
        int $stock_after,
        string $operation
    ): StockMovement;
}