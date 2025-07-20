<?php

namespace App\Http\Controllers\Api\Order;

use App\Contracts\Order\OrderServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CompleteOrderRequest;
use Exception;
use Illuminate\Http\JsonResponse;

final class CompleteOrderController extends Controller
{
    public function __invoke(
        CompleteOrderRequest $request,
        OrderServiceInterface $orderService,
        int $orderID
    ): JsonResponse
    {
        try {
            $order = $orderService->completeOrder($orderID);

            return response()->json([
                'success' => true,
                'data' => $order,
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 400);
        }
    }
}
