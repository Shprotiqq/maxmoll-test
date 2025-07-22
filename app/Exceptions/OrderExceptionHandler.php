<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Throwable;

class OrderExceptionHandler extends ExceptionHandler
{

    protected $dontReport = [];

    /**
     * Регистрация обработчиков исключений.
     */
    public function register(): void
    {
        $this->renderable(function (Throwable $e) {
            return $this->handleException($e);
        });
    }

    /**
     * Обработка исключений и возврат JSON-ответа.
     *
     * @param Throwable $e
     * @return JsonResponse
     */
    protected function handleException(Throwable $e): JsonResponse
    {
        if ($e instanceof OrderCreationException || $e instanceof NegativeCostException || InvalidChangeStockOperationException::class || OrderCompleteException::class || OrderCancelException::class) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error_code' => 'UNKNOWN_ERROR'
            ], 500);
        }

        return response()->json([
            'success' => false,
            'message' => 'Неизвестная ошибка',
            'error_code' => 'UNKNOWN_ERROR'
        ], 500);
    }
}
