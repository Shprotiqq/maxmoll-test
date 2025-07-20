<?php

namespace App\Http\Controllers\Api\Warehouse;

use App\Contracts\Warehouse\WarehouseServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetWithFiltersRequest;
use Illuminate\Http\JsonResponse;

final class IndexWarehouseController extends Controller
{
    public function getWarehouses(
        GetWithFiltersRequest $request,
        WarehouseServiceInterface $warehouseService
    ): JsonResponse {

        $warehouses = $warehouseService->getWarehousesWithStockInfo($request->toDTO());

        return response()->json([
            'success' => true,
            'data' => $warehouses
        ]);
    }
}
