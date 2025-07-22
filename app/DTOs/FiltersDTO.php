<?php

namespace App\DTOs;

/**
 * Класс FiltersDTO
 *
 * DTO для передачи параметров фильтрации и пагинации данных.
 */
final readonly class FiltersDTO
{
    /**
     * Константа, задающая количество записей на страницу по умолчанию.
     */
    public const DEFAULT_PER_PAGE = 10;

    /**
     * @param int $per_page Количество элементов на странице (пагинация).
     * @param array $filters Ассоциативный массив фильтров.
     */
    public function __construct(
        public int $per_page = self::DEFAULT_PER_PAGE,
        public array $filters,
    ) {
    }
}