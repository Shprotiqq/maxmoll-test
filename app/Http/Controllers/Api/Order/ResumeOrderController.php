<?php

namespace App\Http\Controllers\Api\Order;

use App\Contracts\Order\OrderServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\ResumeOrderRequest;
use Illuminate\Http\JsonResponse;

final class ResumeOrderController extends Controller
{
    public function resumeOrder(ResumeOrderRequest $request, OrderServiceInterface $orderService): JsonResponse
    {
        $dto = $request->toDTO();

        $order = $orderService->resumeOrder($dto);

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }
}
