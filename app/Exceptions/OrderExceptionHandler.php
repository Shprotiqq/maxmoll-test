<?php

namespace App\Exceptions;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

/**
 * Класс OrderExceptionHandler
 *
 * Обработчик исключений для заказов.
 */
class OrderExceptionHandler extends ExceptionHandler
{
    protected $dontReport = [];

    public function register(): void
    {
        $this->renderable(function (Throwable $e) {
            return $this->handleException($e);
        });
    }

    /**
     * Обработка исключений с возвратом ответа.
     *
     * @param Throwable $e Исключение, которое необходимо обработать.
     * @return JsonResponse|Response JSON-ответ для известных исключений или текстовая ошибка для неизвестных.
     */
    protected function handleException(Throwable $e): JsonResponse|Response
    {
        if ($e instanceof OrderCreationException ||
            $e instanceof NegativeCostException ||
            InvalidChangeStockOperationException::class ||
            OrderCompleteException::class ||
            OrderCancelException::class)
        {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error_code' => 'KNOWN_ERROR'
            ], 400);
        }

        return response(
            'Произошла непредвиденная ошибка. Пожалуйста, попробуйте позже.',
            500,
            ['Content-Type' => 'text/plain']
        );
    }
}
