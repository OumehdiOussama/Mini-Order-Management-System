<?php

namespace App\Http\Requests;

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
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $user = $this->user();
        if ($user->role === 'customer' && $user->customer) {
            $this->merge([
                'customer_id' => $user->customer->id,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'products' => 'required|array|min:1',
            'products.*' => 'required|integer|distinct|exists:products,id',
            'quantities' => 'required|array',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $products = $this->input('products', []);
            $quantities = $this->input('quantities', []);

            foreach ($products as $productId) {
                if (!array_key_exists($productId, $quantities)) {
                    $validator->errors()->add('quantities', 'Each selected product must have a quantity');
                    return;
                }

                $quantity = $quantities[$productId] ?? 0;
                
                if (!is_numeric($quantity) || $quantity <= 0) {
                    $validator->errors()->add('quantities', 'All ordered quantities must be greater than 0');
                    return;
                }
            }
        });
    }
}
