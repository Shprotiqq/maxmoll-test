<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResumeOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\Request;

class ResumeOrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    )
    {
    }

    public function __invoke(ResumeOrderRequest $request, int $orderId)
    {
        try {
            $order = $this->orderService->resumeOrder($orderId);

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
