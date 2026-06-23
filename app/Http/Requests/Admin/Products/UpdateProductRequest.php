<?php

namespace App\Http\Requests\Admin\Products;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\ProductVariantImage;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        $productId = $this->route('id'); 

        return [
            // Product
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'name')->ignore($productId),
            ],
            
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|boolean',
            'is_best_seller' => 'required|boolean',

            // Categories
            'categories' => 'nullable|array|min:1',
            'categories.*' => 'exists:categories,id',

            // Variants
            'variants' => 'required|array|min:1',
            'variants.*.id' => [
                'nullable',
                Rule::exists('product_variants', 'id')->where('product_id', $productId),
            ],
            'variants.*.color_name' => 'required|string|max:255',
            'variants.*.color_code' => 'nullable|string|max:20',
            'variants.*.status' => 'required|boolean',

            // New Images
            'variants.*.new_images' => 'nullable|array|min:1',
            'variants.*.new_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',

            // Deleted Images
            'variants.*.deleted_images' => 'nullable|array|min:1',
            'variants.*.deleted_images.*' => [
                'integer',
                function ($attribute, $value, $fail) {
                    $parts = explode('.', $attribute);
                    $variantIndex = $parts[1] ?? null;
                    $variantId = $this->input("variants.$variantIndex.id");

                    if ($variantId) {
                        $exists = ProductVariantImage::where('id', $value)
                            ->where('product_variant_id', $variantId)
                            ->exists();
                        if (!$exists) {
                            $fail("The selected image ID $value is invalid for variant $variantId.");
                        }
                    }
                }
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'variants.required' => 'At least one variant is required.',
            'variants.*.new_images.min' => 'Each variant must have at least one new image.',
        ];
    }
}
