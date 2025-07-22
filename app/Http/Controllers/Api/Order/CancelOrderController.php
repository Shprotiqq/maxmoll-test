<?php

namespace App\Http\Controllers\Api\Order;

use App\Contracts\Order\OrderServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CancelOrderRequest;
use Illuminate\Http\JsonResponse;

/**
 * Класс CancelOrderController
 *
 * Контроллер для обработки REST-запросов на отмену заказа.
 */
final class CancelOrderController extends Controller
{
    /**
     * Отменяет заказ на основе переданных данных.
     *
     * @param CancelOrderRequest $request Запрос с данными для отмены заказа.
     * @param OrderServiceInterface $orderService Сервис для работы с заказами.
     * @return JsonResponse JSON-ответ с информацией об успехе операции и данными заказа.
     */
    public function cancelOrder(
        CancelOrderRequest $request,
        OrderServiceInterface $orderService,
    ): JsonResponse
    {
        $dto = $request->toDTO();

        $order = $orderService->cancelOrder($dto);

        return response()->json([
            'success' => true,
            'data' => $order,
            'message' => 'Заказ успешно отменен',
        ]);
    }
}