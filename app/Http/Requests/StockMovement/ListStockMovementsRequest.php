<?php

namespace App\Http\Requests\StockMovement;

use App\DTOs\StockMovement\ListStockMovementDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Класс ListStockMovementsRequest
 *
 * Форма запроса для валидации данных при получении списка движений товаров с фильтрами и пагинацией.
 */
class ListStockMovementsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'warehouse_id' => 'nullable|integer|exists:warehouses,id',
            'product_id' => 'nullable|integer|exists:products,id',
            'date_from' => 'nullable|date',
            'per_page' => 'integer|min:1|max:100',
        ];
    }

    /**
     * Преобразует данные запроса в DTO для фильтрации движений товаров.
     *
     * @return ListStockMovementDTO Объект с данными фильтрации и пагинации.
     */
    public function getDTO(): ListStockMovementDTO
    {
        return new ListStockMovementDTO(
            product_id: $this->input('product_id'),
            warehouse_id: $this->input('warehouse_id'),
            date_from: $this->input('date_from'),
            per_page: $this->input('per_page', 10)
        );
    }
}