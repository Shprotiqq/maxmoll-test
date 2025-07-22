<?php

namespace App\Http\Controllers\Api\Product;

use App\Contracts\Product\ProductServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetWithFiltersRequest;
use Illuminate\Http\JsonResponse;

/**
 * Класс IndexProductController
 *
 * Контроллер для обработки REST-запросов на получение списка продуктов с указанием склада и учетом фильтров и пагинации.
 */
final class IndexProductController extends Controller
{
    /**
     * @param GetWithFiltersRequest $request Запрос с фильтрами для получения списка продуктов.
     * @param ProductServiceInterface $productService Сервис для работы с продуктами.
     * @return JsonResponse JSON-ответ с информацией об успехе операции и списком продуктов.
     */
    public function getProducts(GetWithFiltersRequest $request, ProductServiceInterface $productService): JsonResponse
    {
        $dto = $request->toDTO();

        $products = $productService->getProductsWithStocks($dto);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
}
