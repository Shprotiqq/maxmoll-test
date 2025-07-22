<?php

namespace App\Contracts\Product;

use App\DTOs\FiltersDTO;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Интерфейс ProductServiceInterface
 *
 * Определяет контракт для сервиса управления продуктами, предоставляя методы для работы с продуктами и их остатками.
 */
interface ProductServiceInterface
{
    /**
     * Получает список продуктов с информацией об их остатках на складах с применением фильтров и пагинацией.
     *
     * @param FiltersDTO $dto Объект DTO с параметрами фильтрации (например, название продукта, склад, диапазон цен).
     * @return LengthAwarePaginator Пагинированный список продуктов с подгруженными данными об остатках.
     */
    public function getProductsWithStocks(FiltersDTO $dto): LengthAwarePaginator;
}