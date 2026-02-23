<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateImportRequest extends FormRequest
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
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'cost_price_usd' => 'required|numeric|min:0',
            'exchange_rate' => 'required|numeric|min:0',
            'extra_fees_brl' => 'nullable|numeric|min:0',
            'total_cost_brl' => 'required|numeric|min:0',
            'store_name' => 'required|string|max:255',
            'import_date' => 'required|date',
        ];
    }
}