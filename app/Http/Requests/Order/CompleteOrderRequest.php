<?php

namespace App\Http\Requests\Order;

use App\DTOs\Order\CompleteOrderDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Класс CompleteOrderRequest
 *
 * Форма запроса для валидации данных при завершении заказа.
 */
final class CompleteOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Правила валидации для идентификатора заказа
        return [
            'order_id' => 'required|int|exists:orders,id',
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.exists' => 'Заказ не найден',
        ];
    }

    /**
     * Преобразует данные запроса в DTO для завершения заказа.
     *
     * @return CompleteOrderDTO Объект с данными для завершения заказа.
     */
    public function toDTO(): CompleteOrderDTO
    {
        return new CompleteOrderDTO(
            order_id: $this->input('order_id'),
        );
    }
}