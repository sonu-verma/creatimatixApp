<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EventUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('events', 'title')->ignore($this->id), 
            ],
            'userName' => 'nullable|string|max:255',
            'registrationStartDate' => 'required|date_format:Y-m-d',
            'registrationEndDate' => 'required|date_format:Y-m-d|after_or_equal:registrationStartDate',
            'eventStartDate' => 'required',
            'eventEndDate' => 'required|after_or_equal:eventStartDate',
            'registrationAmount' => 'required|numeric|min:0',
            'teamLimit' => 'nullable|integer|min:1',
            'sportsType' => 'required|string|max:255',
            'eventType' => 'required|in:individual,team',
            'locationLat' => 'required|string',
            'locationLon' => 'required|string',
            'bannerFile' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:20480',
            'description' => 'required|string',
            'rules' => 'nullable|string',
            'isActive' => 'nullable|string',
            'address' => 'required|string',
        ];
    }
}


