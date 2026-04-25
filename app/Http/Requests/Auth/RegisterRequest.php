<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name" => "required|string|max:50",
            "phone"=>"nullable|string|unique:users,phone|regex:/^\+?[0-9]{7,15}$/",
            "email" => "required|string|max:255|email|unique:users,email",
            "password" => "required|string|min:6|confirmed"
        ];
    }
}
