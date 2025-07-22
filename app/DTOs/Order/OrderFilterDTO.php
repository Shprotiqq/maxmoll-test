<?php

namespace App\DTOs\Order;

/**
 * Класс OrderFilterDTO
 *
 * DTO для передачи параметров фильтрации заказов.
 */
final readonly class OrderFilterDTO
{
    /**
     * Константа, задающая количество записей на страницу по умолчанию.
     */
    public const DEFAULT_PER_PAGE = 10;

    /**
     * @param string|null $customer Имя для фильтрации заказов, необязательный параметр.
     * @param string|null $status Статус заказа для фильтрации (например, 'active', 'completed', 'canceled'), необязательный параметр.
     * @param string|null $warehouse Название или идентификатор склада для фильтрации заказов, необязательный параметр.
     * @param string|null $date_from Начальная дата для фильтрации заказов (формат: YYYY-MM-DD), необязательный параметр.
     * @param string|null $date_to Конечная дата для фильтрации заказов (формат: YYYY-MM-DD), необязательный параметр.
     * @param int $per_page Количество записей на страницу, по умолчанию используется self::DEFAULT_PER_PAGE.
     */
    public function __construct(
        public ?string $customer = null,
        public ?string $status = null,
        public ?string $warehouse = null,
        public ?string $date_from = null,
        public ?string $date_to = null,
        public int $per_page = self::DEFAULT_PER_PAGE,
    ) {
    }
}