<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = Auth::id();

        return [
            'name'  => 'required|string|max:100',
            'email' => [
                'required',
                'string',
                'email',
                Rule::unique('users', 'email')->ignore($id),
            ],
            // Phone is optional — not all users may have set one.
            // When provided, must be a valid Moroccan number and unique
            // (ignoring the current user's own number).
            'phone' => [
                'nullable',
                'string',
                'regex:/^(0[67][0-9]{8}|\+212[67][0-9]{8})$/',
                Rule::unique('users', 'phone')->ignore($id)->whereNotNull('phone'),
            ],
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'   => 'Your name is required.',
            'email.required'  => 'Your email is required.',
            'email.unique'    => 'This email is already used by another account.',
            'phone.regex'     => 'Phone must be a valid Moroccan number (e.g. 0612345678 or +212612345678).',
            'phone.unique'    => 'This phone number is already used by another account.',
        ];
    }
}
