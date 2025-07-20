<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

final class CompleteOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }
}
