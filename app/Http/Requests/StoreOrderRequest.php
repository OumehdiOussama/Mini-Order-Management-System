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
     * Auto-inject customer_id for customer role users.
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
     * Validation rules.
     *
     * Note: 'products' is an associative array keyed by product ID:
     *   products[1] = "1", products[5] = "5"
     * This is intentional so OrderService can look up quantities[productId].
     */
    public function rules(): array
    {
        return [
            'customer_id'  => 'required|exists:customers,id',
            'products'     => 'required|array|min:1',
            'products.*'   => 'required|integer|exists:products,id',
            'quantities'   => 'required|array',
            'quantities.*' => 'nullable|integer|min:1|max:999',
        ];
    }

    /**
     * Extra validation: every selected product must have a valid quantity.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $products   = $this->input('products', []);   // [productId => productId]
            $quantities = $this->input('quantities', []); // [productId => qty]

            foreach ($products as $productId => $val) {
                $qty = $quantities[$productId] ?? 0;

                if (!is_numeric($qty) || (int) $qty < 1) {
                    $validator->errors()->add(
                        'quantities',
                        'All selected products must have a quantity of at least 1.'
                    );
                    return;
                }
            }
        });
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'products.required'     => 'Please select at least one product.',
            'products.min'          => 'Please select at least one product.',
            'customer_id.required'  => 'Please select a customer.',
            'customer_id.exists'    => 'The selected customer does not exist.',
            'quantities.*.min'      => 'Each quantity must be at least 1.',
            'quantities.*.max'      => 'Quantity cannot exceed 999 per product.',
        ];
    }
}
