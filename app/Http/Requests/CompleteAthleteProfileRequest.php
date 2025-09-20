<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompleteAthleteProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Will be handled by auth middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20|unique:users,phone_number',
            'gender' => 'required|in:male,female,other',
            'weight' => 'nullable|numeric|min:0',
            'photo' => 'nullable|file|image|max:5120', // 5MB max
            'training_frequency' => 'required|integer|in:2,3,4,5,6,7',
            'inbody_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB max
        ];
    }
}
