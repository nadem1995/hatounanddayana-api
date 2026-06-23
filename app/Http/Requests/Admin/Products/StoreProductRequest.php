<?php

namespace App\Http\Requests\Admin\Products;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            // --------------------
            // Product
            // --------------------
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
            'is_best_seller' => 'required|boolean',
            'price' => 'required|numeric|min:0',

            // --------------------
            // Variants
            // --------------------

            'variants' => 'required|array|min:1',

            'variants.*.color_name' => 'required|string|max:100',

            'variants.*.color_code' => 'nullable|string|max:20',


            // --------------------
            // Images
            // --------------------

            'variants.*.images' => 'required|array|min:1',
            'variants.*.images.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',

            // --------------------
            // Categories
            // --------------------

            'categories' => 'required|array|min:1',
            'categories.*' => 'integer|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'variants.required' => 'At least one variant is required.',
            'variants.*.images.min' => 'Each variant must have at least one image.',
        ];
    }
}
