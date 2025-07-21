<?php

namespace App\Http\Controllers\Api\Order;

use App\Contracts\Order\OrderServiceInterface;
use App\Exceptions\NegativeCostException;
use App\Exceptions\OrderCreationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CreateOrderRequest;
use Illuminate\Http\JsonResponse;
use Throwable;

final class CreateOrderController extends Controller
{
    public function createOrder(CreateOrderRequest $request, OrderServiceInterface $orderService): JsonResponse
    {
        $dto = $request->toDTO();

        try {
            $order = $orderService->createOrder($dto);

            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Заказ успешно создан'
            ], 201);
        } catch (OrderCreationException|NegativeCostException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error_code' => 'UNKNOWN_ERROR'
            ], 500);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Неизвестная ошибка',
                'error_code' => 'UNKNOWN_ERROR'
            ], 500);
        }
    }
}
