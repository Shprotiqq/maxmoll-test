<?php

namespace App\Http\Controllers\Api\Order;

use App\Contracts\Order\OrderServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\ResumeOrderRequest;
use Exception;
use Illuminate\Http\JsonResponse;

final class ResumeOrderController extends Controller
{
    public function __invoke(
        ResumeOrderRequest $request,
        OrderServiceInterface $orderService,
        int $orderId
    ): JsonResponse
    {
        try {
            $order = $orderService->resumeOrder($orderId);

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
