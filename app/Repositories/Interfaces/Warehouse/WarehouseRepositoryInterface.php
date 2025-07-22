<?php

namespace App\Repositories\Interfaces\Warehouse;

use App\DTOs\FiltersDTO;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Интерфейс WarehouseRepositoryInterface
 *
 * Определяет контракт для работы со складами, включая метод для получения списка складов с информацией об остатках товаров.
 */
interface WarehouseRepositoryInterface
{
    /**
     * Получает список складов с информацией об остатках товаров с учетом фильтров и пагинации.
     *
     * @param FiltersDTO $dto Объект с данными фильтрации и пагинации.
     * @return LengthAwarePaginator Пагинированный список складов с информацией об остатках.
     */
    public function getWarehousesWithStockInfo(FiltersDTO $dto): LengthAwarePaginator;
}