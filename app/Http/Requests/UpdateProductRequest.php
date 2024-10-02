<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
        $productIdToIgnore = $this->route('product')['id'];

        return [
            'image' => ['sometimes', 'string', 'nullable'],
            'name' => ['required', 'string', 'min:3', 'max:100', 'unique:products,name,' . $productIdToIgnore],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0', 'max:10000'],
            'stock' => ['required', 'integer', 'min:0', 'max:1000'],
            'description' => ['required', 'string', 'min: 10', 'max:250']
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('productImageUrl')) {
            $this->merge([
                'image' => $this->input('productImageUrl'),
            ]);
        }

        if ($this->has('categoryId')) {
            $this->merge([
                'category_id' => $this->input('categoryId'),
            ]);
        }

        if ($this->has('price')) {
            $this->merge([
                'price' => round($this->input('price'), 2),
            ]);
        }
    }
}
