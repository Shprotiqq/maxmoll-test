<?php

namespace App\Http\Controllers\Api\Order;

use App\Contracts\Order\OrderServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CompleteOrderRequest;
use Illuminate\Http\JsonResponse;

/**
 * Класс CompleteOrderController
 *
 * Контроллер для обработки REST-запросов на завершение заказа.
 */
final class CompleteOrderController extends Controller
{
    /**
     * Завершает заказ на основе переданных данных.
     *
     * @param CompleteOrderRequest $request Запрос с данными для завершения заказа.
     * @param OrderServiceInterface $orderService Сервис для работы с заказами.
     * @return JsonResponse JSON-ответ с информацией об успехе операции и данными заказа.
     */
    public function completeOrder(CompleteOrderRequest $request, OrderServiceInterface $orderService): JsonResponse
    {
        $dto = $request->toDTO();

        $order = $orderService->completeOrder($dto);

        return response()->json([
            'success' => true,
            'data' => $order,
            'message' => 'Заказ успешно завершен',
        ]);
    }
}