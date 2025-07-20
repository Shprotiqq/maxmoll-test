<?php

namespace App\Http\Controllers\Api\Order;

use App\Contracts\Order\OrderServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CancelOrderRequest;
use Exception;
use Illuminate\Http\JsonResponse;

final class CancelOrderController extends Controller
{

    public function __invoke(
        CancelOrderRequest $request,
        OrderServiceInterface $orderService,
        int $orderId
    ): JsonResponse {
        try {
            $order = $orderService->cancelOrder($orderId);

            return response()->json([
                'success' => true,
                'data' => $order
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 400);
        }
    }
}
