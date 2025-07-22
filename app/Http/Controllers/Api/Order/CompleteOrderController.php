<?php

namespace App\Http\Controllers\Api\Order;

use App\Contracts\Order\OrderServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CompleteOrderRequest;
use Illuminate\Http\JsonResponse;

final class CompleteOrderController extends Controller
{
    public function completeOrder(CompleteOrderRequest $request, OrderServiceInterface $orderService): JsonResponse
    {
        $dto = $request->toDTO();

        $order = $orderService->completeOrder($dto);

        return response()->json([
            'success' => true,
            'data' => $order,
            'message' => 'Заказ успешно завершен',
        ]);
    }
}
