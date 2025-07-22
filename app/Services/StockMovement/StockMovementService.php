<?php

namespace App\Services\StockMovement;

use App\Contracts\StockMovement\StockMovementServiceInterface;
use App\DTOs\StockMovement\ListStockMovementDTO;
use App\Repositories\Interfaces\StockMovement\StockMovementRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Сервис класс StockMovementService
 *
 * Реализует сервис для управления движениями товаров на складах, предоставляя методы
 * для получения списка движений товаров с фильтрацией и пагинацией.
 */
final readonly class StockMovementService implements StockMovementServiceInterface
{
    /**
     * @param StockMovementRepositoryInterface $stockMovementRepository Репозиторий для работы с движениями товаров.
     */
    public function __construct(
        private StockMovementRepositoryInterface $stockMovementRepository,
    ) {
    }

    /**
     * Получает список движений товаров с применением фильтров и пагинацией.
     *
     * @param ListStockMovementDTO $dto Объект DTO с параметрами фильтрации (например, склад, продукт, дата).
     * @return LengthAwarePaginator Пагинированный список движений товаров с подгруженными связями.
     */
    public function getStockMovements(ListStockMovementDTO $dto): LengthAwarePaginator
    {
        return $this->stockMovementRepository->getStockMovements($dto);
    }
}