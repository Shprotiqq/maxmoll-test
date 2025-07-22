<?php

namespace App\Services\Warehouse;

use App\Contracts\Warehouse\WarehouseServiceInterface;
use App\DTOs\FiltersDTO;
use App\Repositories\Interfaces\Warehouse\WarehouseRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Сервис класс WarehouseService
 *
 * Реализует сервис для управления складами, предоставляя методы для получения списка складов
 * с информацией об их остатках.
 */
final readonly class WarehouseService implements WarehouseServiceInterface
{
    /**
     * @param WarehouseRepositoryInterface $warehouseRepository Репозиторий для работы со складами и их остатками.
     */
    public function __construct(
        private WarehouseRepositoryInterface $warehouseRepository
    ) {
    }

    /**
     * Получает список складов с информацией об остатках товаров с применением фильтров и пагинацией.
     *
     * @param FiltersDTO $dto Объект DTO с параметрами фильтрации (например, название склада, продукт, диапазон остатков).
     * @return LengthAwarePaginator Пагинированный список складов с подгруженными данными об остатках.
     */
    public function getWarehousesWithStockInfo(FiltersDTO $dto): LengthAwarePaginator
    {
        return $this->warehouseRepository->getWarehousesWithStockInfo($dto);
    }
}