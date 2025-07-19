<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetProductsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'per_page' => 'sometimes|integer|min:1|max:100',
            'name' => 'sometimes|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'per_page.integer' => 'Количество элементов на странице должно быть целым числом.',
            'per_page.min' => 'Минимальное количество элементов на странице - 1',
            'per_page.max' => 'Максимальное количество элементов на странице - 100',
            'name.string' => 'Название товара должно быть строкой',
            'name.max' => 'Максимальная длина названия товара - 255 символов'
        ];
    }
}
