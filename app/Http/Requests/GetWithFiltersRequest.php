<?php

namespace App\Http\Requests;

use App\DTOs\FiltersDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Класс GetWithFiltersRequest
 *
 * Форма запроса для валидации данных при получении списков сущностей с фильтрами и пагинацией.
 */
final class GetWithFiltersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'per_page' => 'sometimes|int|min:1|max:100',
            'filters' => 'sometimes|array',
            'filters.name' => 'sometimes|string',
        ];
    }

    public function messages(): array
    {
        return [
            'per_page.integer' => 'Количество элементов на странице должно быть целым числом.',
            'per_page.min' => 'Минимальное количество элементов на странице - 1',
            'per_page.max' => 'Максимальное количество элементов на странице - 100',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'per_page' => $this->input('per_page', FiltersDTO::DEFAULT_PER_PAGE),
            'filters' => $this->input('filters', []),
        ]);
    }

    /**
     * Преобразует данные запроса в DTO для фильтрации и пагинации.
     *
     * @return FiltersDTO Объект с данными фильтрации и пагинации.
     */
    public function toDTO(): FiltersDTO
    {
        return new FiltersDTO(
            per_page: $this->input('per_page', FiltersDTO::DEFAULT_PER_PAGE),
            filters: $this->input('filters', []),
        );
    }
}