<?php

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

class GetOrdersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'per_page' => 'sometimes|integer|min:1|max:100',
            'status' => 'sometimes|string|in:active,completed,cancelled',
            'customer' => 'sometimes|string|exists:customers|max:255',
            'warehouse_id' => 'sometimes|int|exists:warehouses|max:255',
            'date_from' => 'sometimes|date',
            'date_to' => 'sometimes|date|after_or_equal:date_from',
        ];
    }

    public function messages(): array
    {
        return [
          'status.in' => 'Указанного статуса не существует',
          'warehouse_id.exists' => 'Указанный склад не существует',
          'customer.exists' => 'Указанный покупатель не существует',
          'date_to.after_or_equal' => 'Конечная дата должна быть позже или равна начальной'
        ];
    }
}
