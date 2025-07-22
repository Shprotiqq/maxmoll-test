<?php

namespace App\DTOs\StockMovement;

/**
 * Класс ListStockMovementDTO
 *
 * DTO для передачи параметров фильтрации и пагинации данных для получения истории операций.
 */
final readonly class ListStockMovementDTO
{
    /**
     * Константа, задающая количество записей на страницу по умолчанию.
     */
    public const DEFAULT_PER_PAGE = 10;


    /**
     * @param int|null $product_id Необязательный идентификатор продукта, по которому может происходить поиск.
     * @param int|null $warehouse_id Необязательный идентификатор склада, по которому может происходить поиск.
     * @param string|null $date_from Необязательный параметр даты, по которому может происходить поиск.
     * @param int $per_page Количество элементов на странице (пагинация).
     */
    public function __construct(
        public ?int $product_id = null,
        public ?int $warehouse_id = null,
        public ?string $date_from = null,
        public int $per_page = self::DEFAULT_PER_PAGE
    )
    {
    }
}