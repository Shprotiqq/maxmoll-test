<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetOrdersRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    )
    {
    }

    public function __invoke(GetOrdersRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $orders = $this->orderService->getOrders(
            $validated['per_page'] ?? 10,
            [
                'status' => $validated['status'] ?? null,
                'customer' => $validated['customer'] ?? null,
                'warehouse_id' => $validated['warehouse_id'] ?? null,
                'date_from' => $validated['date_from'] ?? null,
                'date_to' => $validated['date_to'] ?? null,
            ]
        );

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }
}
