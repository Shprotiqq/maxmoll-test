<?php

namespace App\Http\Controllers\Api\StockMovement;

use App\Contracts\StockMovement\StockMovementServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StockMovement\ListStockMovementsRequest;
use Illuminate\Http\JsonResponse;

final class GetStockMovementController extends Controller
{
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
