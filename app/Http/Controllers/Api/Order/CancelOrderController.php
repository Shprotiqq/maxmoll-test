<?php

namespace App\Http\Controllers\Api\Order;

use App\Contracts\Order\OrderServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CancelOrderRequest;
use Illuminate\Http\JsonResponse;

final class CancelOrderController extends Controller
{
    public function cancelOrder(
        CancelOrderRequest $request,
        OrderServiceInterface $orderService,
    ): JsonResponse
    {
        $dto = $request->toDTO();

        $order = $orderService->cancelOrder($dto);

        return response()->json([
            'success' => true,
            'data' => $order,
            'message' => 'Заказ успешно отменен',
        ]);
    }
}
