<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'password' => $this->password ? trim($this->password) : null,
            'phone' => $this->phone ? trim($this->phone) : null,
        ]);

        return [
            "name" => "required",
            "email" => "required|email|unique:users,email",
            "phone" => "required|unique:users,phone",
            "password" => "required",
            "role" => "required",
            "gender" => "required",
            "profile" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
            "short_desc" => "nullable|string",
        ];
    }


    public function messages(): array
    {
        return [
            "name.required" => "Name is required",
            "email.required" => "Email is required",
            "email.email" => "Email must be a valid email address",
            "email.unique" => "Email already exists",
            "phone.required" => "Phone number is required",
            "phone.unique" => "Phone number already exists",
            "password.required" => "Password is required",
            "role.required" => "Role is required",
            "profile.mimes" => "Please upload a valid image file. | jpeg,png,jpg,gif",
        ];
    }
}
