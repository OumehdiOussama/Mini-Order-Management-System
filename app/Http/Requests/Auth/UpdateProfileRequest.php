<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

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
            "email" => "required|string|max:255|email|unique:users,email,".$id,
        ];
    }
}
