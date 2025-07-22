<?php

namespace App\Http\Requests\Order;

use App\DTOs\Order\OrderFilterDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Класс GetOrdersRequest
 *
 * Форма запроса для валидации данных при получении списка заказов с фильтрами и пагинацией.
 */
final class GetOrdersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer' => 'sometimes|string|max:255|exists:orders,customer',
            'status' => 'sometimes|string|in:active,completed,cancelled',
            'warehouse' => 'sometimes|integer|exists:warehouses,name',
            'date_from' => 'sometimes|date',
            'date_to' => 'sometimes|date|after_or_equal:date_from',
            'per_page' => 'sometimes|integer|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'customer.exists' => 'Указанный покупатель не найден',
            'status.in' => 'Указанного статуса не существует',
            'warehouse.exists' => 'Указанный склад не найден',
            'date_to.after_or_equal' => 'Конечная дата должна быть позже или равна начальной'
        ];
    }

    /**
     * Преобразует данные запроса в DTO для фильтрации заказов.
     *
     * @return OrderFilterDTO Объект с данными фильтрации и пагинации.
     */
    public function toDTO(): OrderFilterDTO
    {
        // Создание DTO с параметрами фильтрации и пагинации, с использованием значения по умолчанию для per_page
        return new OrderFilterDTO(
            customer: $this->input('customer'),
            status: $this->input('status'),
            warehouse: $this->input('warehouse'),
            date_from: $this->input('date_from'),
            date_to: $this->input('date_to'),
            per_page: $this->input('per_page', OrderFilterDTO::DEFAULT_PER_PAGE),
        );
    }
}