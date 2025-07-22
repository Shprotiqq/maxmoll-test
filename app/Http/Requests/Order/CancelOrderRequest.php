<?php

namespace App\Http\Requests\Order;

use App\DTOs\Order\CancelOrderDTO;
use Illuminate\Foundation\Http\FormRequest;

final class CancelOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|integer|exists:orders,id',
        ];
    }

    public function toDTO(): CancelOrderDTO
    {
        return new CancelOrderDTO(
            order_id: $this->input('order_id')
        );
    }
}
