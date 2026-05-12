<?php

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
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
        $requireTracking = $this->input('status') === 'shipped';

        return [
            'status' => 'required|in:' . implode(',', Order::STATUSES),
            'notes' => 'nullable|string',
            'tracking_number' => $requireTracking ? 'required|string|max:255' : 'nullable|string|max:255',
            'carrier' => $requireTracking ? 'required|string|max:255' : 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'tracking_number.required' => 'Tracking number is required when shipping an order.',
            'carrier.required' => 'Carrier is required when shipping an order.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $order = $this->route('order');
            $status = $this->input('status');

            if ($order && $status && !$order->canTransitionTo($status)) {
                $validator->errors()->add('status', 'Invalid status transition from ' . ucfirst($order->status) . ' to ' . ucfirst($status) . '.');
            }
        });
    }
}
