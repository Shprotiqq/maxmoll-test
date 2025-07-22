<?php

namespace App\Http\Requests\Order;

use App\DTOs\Order\CompleteOrderDTO;
use Illuminate\Foundation\Http\FormRequest;

final class CompleteOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
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

    public function toDTO(): CompleteOrderDTO
    {
        return new CompleteOrderDTO(
            order_id: $this->input('order_id'),
        );
    }
}
