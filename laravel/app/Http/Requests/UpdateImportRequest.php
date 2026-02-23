<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateImportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => 'sometimes|exists:products,id',
            'quantity' => 'sometimes|integer|min:1',
            'cost_price_usd' => 'sometimes|numeric|min:0',
            'exchange_rate' => 'sometimes|numeric|min:0',
            'extra_fees_brl' => 'nullable|numeric|min:0',
            'total_cost_brl' => 'sometimes|numeric|min:0',
            'store_name' => 'sometimes|string|max:255',
            'import_date' => 'sometimes|date',
        ];
    }
}
