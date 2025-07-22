<?php

namespace App\Http\Controllers\Api\Order;

use App\Contracts\Order\OrderServiceInterface;
use App\Exceptions\OrderResumeException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\ResumeOrderRequest;
use Illuminate\Http\JsonResponse;

/**
 * Класс ResumeOrderController
 *
 * Контроллер для обработки REST-запросов на возврат заказа в работу.
 */
final class ResumeOrderController extends Controller
{
    /**
     * Возвращает заказ в работу.
     *
     * @param ResumeOrderRequest $request Запрос с данными для возврата заказа в работу.
     * @param OrderServiceInterface $orderService Сервис для работы с заказами.
     * @return JsonResponse JSON-ответ с информацией об успехе операции и данными заказа.
     * @throws OrderResumeException Если произошла ошибка при возврате заказа в работу.
     */
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
