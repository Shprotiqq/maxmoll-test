<?php

namespace App\Http\Requests\Warehouse;

use Illuminate\Foundation\Http\FormRequest;

final class GetWarehousesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'per_page' => 'sometimes|integer|min:1|max:100',
            'name' => 'sometimes|string|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'per_page.integer' => 'Количество элементов на странице должно быть целым числом.',
            'per_page.min' => 'Минимальное количество элементов на странице - 1',
            'per_page.max' => 'Максимальное количество элементов на странице - 100'
        ];
    }
}
