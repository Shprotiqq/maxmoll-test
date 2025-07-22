<?php

namespace App\Contracts\Warehouse;

use App\DTOs\FiltersDTO;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Интерфейс WarehouseServiceInterface
 *
 * Определяет контракт для сервиса управления складами, предоставляя методы для работы
 * со складами и информацией об их остатках.
 */
interface WarehouseServiceInterface
{
    /**
     * Получает список складов с информацией об остатках товаров с применением фильтров и пагинацией.
     *
     * @param FiltersDTO $dto Объект DTO с параметрами фильтрации (например, название склада, продукт, диапазон остатков).
     * @return LengthAwarePaginator Пагинированный список складов с подгруженными данными об остатках.
     */
    public function getWarehousesWithStockInfo(FiltersDTO $dto): LengthAwarePaginator;
}