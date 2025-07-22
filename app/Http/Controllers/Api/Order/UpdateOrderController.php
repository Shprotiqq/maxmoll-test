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
    ): JsonResponse
    {
            $dto =  $request->toDTO();

            $order = $orderService->updateOrder($dto);

            return response()->json([
                'success' => true,
                'data' => $order,
            ]);

    }
}
