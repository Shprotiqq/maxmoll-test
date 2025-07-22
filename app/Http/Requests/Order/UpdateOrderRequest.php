<?php

namespace App\Http\Requests\Order;

use App\DTOs\Order\OrderItemFormDTO;
use App\DTOs\Order\UpdateOrderDTO;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|integer|exists:orders,id',
            'customer' => 'sometimes|string',
            'items' => 'sometimes|array|min:1',
            'items.*.product_id' => 'sometimes|int|exists:products,id',
            'items.*.count' => 'required_with:items|int|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.exists' => 'Заказ с таким ID не найден',
            'items.min' => 'Заказ должен содержать хотя бы одну позицию',
            'items.*.count.min' => 'Количество товара должно быть не менее 1',
        ];
    }

    public function toDTO(): UpdateOrderDTO
    {
        return new UpdateOrderDTO(
            order_id: $this->input('order_id'),
            customer: $this->input('customer'),
            items: $this->getOrderItems(),
        );
    }

    private function getOrderItems(): array
    {
        return array_map(
            fn(array $item) => new OrderItemFormDTO(
                product_id: $item['product_id'],
                count: $item['count']
            ),
            $this->input('items')
        );
    }
}
