<?php

namespace App\Http\Controllers\Api\Warehouse;

use App\Contracts\Warehouse\WarehouseServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetWithFiltersRequest;
use Illuminate\Http\JsonResponse;

/**
 * Класс IndexWarehouseController
 *
 * Контроллер для обработки REST-запросов на получение списка складов с указанием склада и учетом фильтров и пагинации.
 */
final class IndexWarehouseController extends Controller
{
    /**
     * @param GetWithFiltersRequest $request Запрос с фильтрами для получения списка складов.
     * @param WarehouseServiceInterface $warehouseService Сервис для работы со складами.
     * @return JsonResponse JSON-ответ с информацией об успехе операции и списком складов.
     */
    public function getWarehouses(
        GetWithFiltersRequest $request,
        WarehouseServiceInterface $warehouseService
    ): JsonResponse
    {
        $dto = $request->toDTO();

        $warehouses = $warehouseService->getWarehousesWithStockInfo($dto);

        return response()->json([
            'success' => true,
            'data' => $warehouses
        ]);
    }
}
