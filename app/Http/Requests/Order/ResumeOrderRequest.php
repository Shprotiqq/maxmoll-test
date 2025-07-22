<?php

namespace App\Http\Requests\Order;

use App\DTOs\Order\ResumeOrderDTO;
use Illuminate\Foundation\Http\FormRequest;

final class ResumeOrderRequest extends FormRequest
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

    public function toDTO(): ResumeOrderDTO
    {
        return new ResumeOrderDTO(
            order_id: $this->input('order_id'),
        );
    }
}
