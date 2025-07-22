<?php

namespace App\Http\Controllers\Api\Order;

use App\Contracts\Order\OrderServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\GetOrdersRequest;
use Illuminate\Http\JsonResponse;

/**
 * Класс IndexOrderController
 *
 * Контроллер для обработки REST-запросов на просмотр списка закаов с учетом фильтров и пагинации.
 */
final class IndexOrderController extends Controller
{
    /**
     * Получает список заказов с учетом фильтров и пагинации.
     *
     * @param GetOrdersRequest $request Запрос с данными для фильтрации и пагинации заказов.
     * @param OrderServiceInterface $orderService Сервис для работы с заказами.
     * @return JsonResponse JSON-ответ с информацией об успехе операции и списком заказов.
     */
    public function getOrders(GetOrdersRequest $request, OrderServiceInterface $orderService): JsonResponse
    {
        $dto = $request->toDTO();

        $orders = $orderService->getOrders($dto);

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }
}