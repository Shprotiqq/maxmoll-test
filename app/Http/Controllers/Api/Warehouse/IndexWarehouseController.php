<?php

namespace App\Http\Controllers\Api\Warehouse;

use App\Contracts\Warehouse\WarehouseServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Warehouse\GetWarehousesRequest;
use Illuminate\Http\JsonResponse;

final class IndexWarehouseController extends Controller
{
    public function getWarehouses(
        GetWarehousesRequest $request,
        WarehouseServiceInterface $warehouseService
    ): JsonResponse {
        $validated = $request->validated();

        $warehouses = $warehouseService->getWarehousesWithStockInfo(
            $validated['per_page'] ?? 10,
            $validated
        );

        return response()->json([
            'success' => true,
            'data' => $warehouses
        ]);
    }
}
