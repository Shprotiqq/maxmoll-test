<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetAllWarehousesRequest;
use App\Services\WarehouseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function __construct(
        private WarehouseService $warehouseService,
    )
    {
    }

    public function __invoke(GetAllWarehousesRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $warehouses = $this->warehouseService->getWarehouses(
            $validated['per_page'] ?? 10
        );

        return response()->json([
            'success' => true,
            'data' => $warehouses
        ]);
    }
}
