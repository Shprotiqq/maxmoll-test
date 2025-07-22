<?php

namespace App\Http\Controllers\Api\StockMovement;

use App\Contracts\StockMovement\StockMovementServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StockMovement\ListStockMovementsRequest;
use Illuminate\Http\JsonResponse;

/**
 * Класс GetStockMovementController
 *
 * Контроллер для обработки REST-запросов на получение истории операций с учетом фильтров.
 */
final class GetStockMovementController extends Controller
{
    /**
     * @param ListStockMovementsRequest $request Запрос с фильтрами на получение истории операций.
     * @param StockMovementServiceInterface $movementService Сервис для работы с историей операций.
     * @return JsonResponse JSON-ответ с информацией об успехе операции и историей операций.
     */
    public function getListStockMovements(
        ListStockMovementsRequest $request,
        StockMovementServiceInterface $movementService
    ): JsonResponse
    {
        $dto = $request->getDto();

        $movements = $movementService->getStockMovements($dto);

        return response()->json($movements);
    }
}
