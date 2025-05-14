<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
       
        return [
            "name" => "required",
            "gender" => "required",
            "profile" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
            "short_desc" => "nullable|string",
        ];
    }


    public function messages(): array
    {
        return [
            "profile.mimes" => "Please upload a valid image file. | jpeg,png,jpg,gif",
        ];
    }
}
