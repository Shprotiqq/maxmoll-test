<?php

namespace App\Http\Controllers\Api\Order;

use App\Contracts\Order\OrderServiceInterface;
use App\DTOs\Order\UpdateOrderDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\UpdateOrderRequest;
use Exception;
use Illuminate\Http\JsonResponse;

final class UpdateOrderController extends Controller
{
    public function updateOrder(
        UpdateOrderRequest $request,
        OrderServiceInterface $orderService,
        int $orderId
    ): JsonResponse
    {
        try {
            $dto = new UpdateOrderDto(
                customer: $request->input('customer'),
                warehouse_id: $request->input('warehouse_id'),
                items: $request->input('items')
            );

            $order = $orderService->updateOrder($orderId, $dto);

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
