<?php

namespace App\Http\Controllers\Api\Order;

use App\Contracts\Order\OrderServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\GetOrdersRequest;
use Illuminate\Http\JsonResponse;

final class IndexOrderController extends Controller
{
    public function getOrders(GetOrdersRequest $request, OrderServiceInterface $orderService): JsonResponse
    {
        $dto = $request->toDTO();

        $orders = $orderService->getOrders($dto);

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }
}
