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

            'name_ar' => 'required|string|max:255|unique:products,name_ar',
            'name_en' => 'required|string|max:255|unique:products,name_en',

            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',

            'status' => 'required|boolean',
            'price' => 'required|numeric|min:0',

            // --------------------
            // Variants
            // --------------------

            'variants' => 'required|array|min:1',

            'variants.*.color_name_ar' => 'required|string|max:100',
            'variants.*.color_name_en' => 'required|string|max:100',

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
