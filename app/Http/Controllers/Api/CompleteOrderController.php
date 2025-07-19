<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompleteOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\Request;

class CompleteOrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
    )
    {
    }

    public function __invoke(CompleteOrderRequest $request, int $orderID)
    {
        try {
            $order = $this->orderService->completeOrder($orderID);

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
