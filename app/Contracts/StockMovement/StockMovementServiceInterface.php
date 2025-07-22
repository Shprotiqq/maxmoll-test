<?php

namespace App\Contracts\StockMovement;

use App\DTOs\StockMovement\ListStockMovementDTO;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Интерфейс StockMovementServiceInterface
 *
 * Определяет контракт для сервиса управления движениями товаров на складах,
 * предоставляя методы для получения списка движений товаров.
 */
interface StockMovementServiceInterface
{
    /**
     * Получает список движений товаров с применением фильтров и пагинацией.
     *
     * @param ListStockMovementDTO $dto Объект DTO с параметрами фильтрации (например, склад, продукт, даты).
     * @return LengthAwarePaginator Пагинированный список движений товаров с подгруженными связями.
     */
    public function getStockMovements(ListStockMovementDTO $dto): LengthAwarePaginator;
}