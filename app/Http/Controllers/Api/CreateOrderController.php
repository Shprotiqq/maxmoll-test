<?php

namespace App\Http\Controllers\Api;

use App\DTOs\CreateOrderDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class CreateOrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    )
    {
    }

    public function __invoke(CreateOrderRequest $request): JsonResponse
    {
        try {
            $dto = new CreateOrderDTO(
                customer: $request->input('customer'),
                warehouse_id: $request->input('warehouse_id'),
                items: $request->input('items')
            );

            $order = $this->orderService->createOrder($dto);

            return response()->json([
                'success' => true,
                'data' => $order
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
               'success' => false,
               'message' => $exception->getMessage()
            ], 400);
        }
    }
}
