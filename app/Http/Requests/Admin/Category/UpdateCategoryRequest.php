<?php

namespace App\Http\Requests\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('id');

        return [
            'name_en' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('categories', 'name_en')->ignore($categoryId),
            ],

            'name_ar' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('categories', 'name_ar')->ignore($categoryId),
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'status' => [
                'sometimes',
                'boolean',
            ],
        ];
    }
}
