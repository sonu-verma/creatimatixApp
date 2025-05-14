<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeamUpdateRequest extends FormRequest
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
        $teamId = $this->route('id'); 
        return [
            'name' =>  [
                'required',
                Rule::unique('teams')
                    ->where(fn($query) => $query->where('id_user', auth()->id()))
                    ->ignore($teamId),
            ],
            // 'required|unique:teams,name,NULL,id,id_user,' . $this->id_user,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'short_desc' => 'nullable',
        ];
    }
}
