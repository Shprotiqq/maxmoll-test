<?php

namespace App\Http\Requests\Order;

use App\DTOs\Order\CreateOrderDTO;
use App\DTOs\Order\OrderItemFormDTO;
use Illuminate\Foundation\Http\FormRequest;

final class CreateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer' => 'required|string|max:255',
            'warehouse_id' => 'required|integer|exists:warehouses,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.count' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'items.min' => 'Заказ должен содержать хотя бы одну позицию',
            'items.*.count.min' => 'Количество товара должно быть не менее 1'
        ];
    }

    public function toDTO(): CreateOrderDTO
    {
        return new CreateOrderDTO(
            customer: $this->input('customer'),
            warehouse_id: $this->input('warehouse_id'),
            items: $this->getOrderItems()
        );
    }

    public function getOrderItems(): array
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
