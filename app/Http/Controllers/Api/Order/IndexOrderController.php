<?php

namespace App\Http\Controllers\Api\Order;

use App\Contracts\Order\OrderServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\GetOrdersRequest;
use Illuminate\Http\JsonResponse;

final class IndexOrderController extends Controller
{
    public function __invoke(GetOrdersRequest $request, OrderServiceInterface $orderService): JsonResponse
    {
        $validated = $request->validated();

        $orders = $orderService->getOrders(
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
