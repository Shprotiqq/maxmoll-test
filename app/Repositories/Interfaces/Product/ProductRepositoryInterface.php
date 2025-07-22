<?php

namespace App\Repositories\Interfaces\Product;

use App\DTOs\FiltersDTO;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Интерфейс ProductRepositoryInterface
 *
 * Определяет контракт для работы с товарами, включая метод для получения списка товаров с остатками на складах.
 */
interface ProductRepositoryInterface
{
    /**
     * Получает список товаров с остатками на складах с учетом фильтров и пагинации.
     *
     * @param FiltersDTO $dto Объект с данными фильтрации и пагинации.
     * @return LengthAwarePaginator Пагинированный список товаров с информацией об остатках.
     */
    public function getProductsWithStocks(FiltersDTO $dto): LengthAwarePaginator;
}