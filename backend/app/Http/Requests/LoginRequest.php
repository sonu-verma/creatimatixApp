<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
        $this->merge([
            'email' => $this->email ? trim($this->email) : null,
            'password' => $this->password ? trim($this->password) : null
        ]);

        return [
            "email" => "required|email",
            "password" => "required"
        ];
    }

    public function messages(): array
    {
        return [
            "email.required" => "Email is required",
            "email.email" => "Email must be a valid email address",
            "password.required" => "Password is required",
        ];
    }
    public function attributes(): array
    {
        return [
            'email' => 'Email',
            'password' => 'Password',
        ];
    }
}
