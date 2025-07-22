<?php

namespace App\Http\Controllers\Api\Order;

use App\Contracts\Order\OrderServiceInterface;
use App\Exceptions\OrderUpdateException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\UpdateOrderRequest;
use Illuminate\Http\JsonResponse;

/**
 * Класс UpdateOrderController
 *
 * Контроллер для обработки REST-запросов на обновление заказа.
 */
final class UpdateOrderController extends Controller
{
    /**
     * @param UpdateOrderRequest $request Запрос с данными для обновления заказа.
     * @param OrderServiceInterface $orderService Сервис для работы с заказами.
     * @return JsonResponse JSON-ответ с информацией об успехе операции и данными заказа.
     * @throws OrderUpdateException Если произошла ошибка при обновлении заказа.
     */
    public function updateOrder(
        UpdateOrderRequest $request,
        OrderServiceInterface $orderService,
    ): JsonResponse
    {
        $dto = $request->toDTO();

        $order = $orderService->updateOrder($dto);

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }
}
