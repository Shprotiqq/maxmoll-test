<?php

namespace App\Http\Controllers\Api\Order;

use App\Contracts\Order\OrderServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CreateOrderRequest;
use Illuminate\Http\JsonResponse;

final class CreateOrderController extends Controller
{
    public function createOrder(CreateOrderRequest $request, OrderServiceInterface $orderService): JsonResponse
    {
        $dto = $request->toDTO();

        $order = $orderService->createOrder($dto);

        return response()->json([
            'success' => true,
            'data' => $order,
            'message' => 'Заказ успешно создан'
        ], 201);

    }
}
