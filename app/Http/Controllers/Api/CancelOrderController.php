<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CancelOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\Request;

class CancelOrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    )
    {
    }

    public function __invoke(CancelOrderRequest $request, int $orderId)
    {
        try {
            $order = $this->orderService->cancelOrder($orderId);

            return response()->json([
                'success' => true,
                'data' => $order
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 400);
        }
    }
}
