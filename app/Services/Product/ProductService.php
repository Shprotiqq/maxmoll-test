<?php

namespace App\Services\Product;

use App\Contracts\Product\ProductServiceInterface;
use App\DTOs\FiltersDTO;
use App\Repositories\Interfaces\Product\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Сервис класс ProductService
 *
 * Реализует сервис для управления продуктами, предоставляя методы для получения списка продуктов
 * с информацией об их остатках на складах.
 */
final readonly class ProductService implements ProductServiceInterface
{
    /**
     * @param ProductRepositoryInterface $productRepository Репозиторий для работы с продуктами и их остатками.
     */
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {
    }

    /**
     * Получает список продуктов с информацией об их остатках на складах с применением фильтров и пагинацией.
     *
     * @param FiltersDTO $dto Объект DTO с параметрами фильтрации (например, название продукта, склад, диапазон цен).
     * @return LengthAwarePaginator Пагинированный список продуктов с подгруженными данными об остатках.
     */
    public function getProductsWithStocks(FiltersDTO $dto): LengthAwarePaginator
    {
        return $this->productRepository->getProductsWithStocks($dto);
    }
}