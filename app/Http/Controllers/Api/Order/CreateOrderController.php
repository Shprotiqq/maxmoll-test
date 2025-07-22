<?php

namespace App\Http\Controllers\Api\Order;

use App\Contracts\Order\OrderServiceInterface;
use App\Exceptions\NegativeCostException;
use App\Exceptions\OrderCreationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CreateOrderRequest;
use Illuminate\Http\JsonResponse;

/**
 * Класс CreateOrderController
 *
 * Контроллер для обработки REST-запросов на создание заказа.
 */
final class CreateOrderController extends Controller
{
    /**
     * Создает новый заказ на основе данных запроса и возвращает результат в формате JSON.
     *
     * @param CreateOrderRequest $request Запрос с данными для создания заказа.
     * @param OrderServiceInterface $orderService Сервис для работы с заказами.
     * @return JsonResponse JSON-ответ с информацией об успехе операции и данными созданного заказа.
     * @throws OrderCreationException Если произошла ошибка при создании заказа.
     * @throws NegativeCostException Если на складе недостаточно товаров для списания.
     */
    public function createOrder(CreateOrderRequest $request, OrderServiceInterface $orderService): JsonResponse
    {
        $dto = $request->toDTO();

        $order = $orderService->createOrder($dto);

        return response()->json([
            'success' => true,
            'data' => $order,
            'message' => 'Заказ успешно создан'
        ], 201);
    }
}