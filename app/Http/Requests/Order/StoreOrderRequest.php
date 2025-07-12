<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'products' => 'required|array|min:1',
            'products.*' => 'required|integer|exists:products,id',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'products.required' => 'Products are required',
            'products.array' => 'Products must be an array',
            'products.min' => 'At least one product is required',
            'products.*.required' => 'Each product ID is required',
            'products.*.integer' => 'Each product ID must be an integer',
            'products.*.exists' => 'One or more products do not exist',
        ];
    }
}
