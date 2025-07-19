<?php

namespace App\Http\Controllers\Api;

use App\DTOs\UpdateOrderDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\Request;

class UpdateOrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
    )
    {
    }

    public function __invoke(UpdateOrderRequest $request, int $orderId)
    {
        try {
            $dto = new UpdateOrderDto(
                customer: $request->input('customer'),
                warehouse_id: $request->input('warehouse_id'),
                items: $request->input('items')
            );

            $order = $this->orderService->updateOrder($orderId, $dto);

            return response()->json([
                'success' => true,
                'data' => $order,
            ]);
        } catch (\Exception $exception) {
            return response()->json([
               'success' => false,
               'message' => $exception->getMessage(),
            ], 400);
        }
    }
}
