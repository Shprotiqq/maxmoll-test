<?php

namespace App\Http\Controllers\Api\Order;

use App\Contracts\Order\OrderServiceInterface;
use App\DTOs\Order\CreateOrderDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CreateOrderRequest;
use Exception;
use Illuminate\Http\JsonResponse;

final class CreateOrderController extends Controller
{
    public function __invoke(CreateOrderRequest $request, OrderServiceInterface $orderService): JsonResponse
    {
        try {
            $dto = new CreateOrderDTO(
                customer: $request->input('customer'),
                warehouse_id: $request->input('warehouse_id'),
                items: $request->input('items')
            );

            $order = $orderService->createOrder($dto);

            return response()->json([
                'success' => true,
                'data' => $order
            ], 201);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 400);
        }
    }
}
