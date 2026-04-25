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
            "name" => "required|string|max:50",
            "phone" => [
                "required",
                "string",
                "regex:/^(0[67][0-9]{8}|\+212[67][0-9]{8})$/",
                Rule::unique("users", "phone")->ignore($id),
            ],            
            "email" => "required|string|email|unique:users,email,".$id,
        ];
    }
}
