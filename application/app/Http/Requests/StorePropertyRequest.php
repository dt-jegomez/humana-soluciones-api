<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->has('consignation_type')) {
            $this->merge([
                'consignation_type' => strtolower((string) $this->input('consignation_type')),
            ]);
        }
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:120'],
            'address' => ['required', 'string', 'max:255'],
            'bedrooms' => ['required', 'integer', 'min:0'],
            'bathrooms' => ['required', 'integer', 'min:0'],
            'consignation_type' => ['required', 'in:rent,sale'],
            'rent_price' => ['nullable', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'area' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'images' => ['nullable', 'array', 'min:1'],
            'images.*.url' => ['required_with:images', 'url'],
            'images.*.description' => ['nullable', 'string', 'max:255'],
            'images.*.is_primary' => ['nullable', 'boolean'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $type = $this->input('consignation_type');
            if (! $type && $this->route('property')) {
                $type = $this->route('property')->consignation_type;
            }

            $property = $this->route('property');

            if ($type === 'rent' && ! $this->filled('rent_price') && (! $property || blank($property->rent_price))) {
                $validator->errors()->add('rent_price', 'El valor de arriendo es obligatorio para consignaciones de arriendo.');
            }

            if ($type === 'sale' && ! $this->filled('sale_price') && (! $property || blank($property->sale_price))) {
                $validator->errors()->add('sale_price', 'El valor de venta es obligatorio para consignaciones de venta.');
            }
        });
    }
}
