<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompleteCoachProfileRequest extends FormRequest
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
            'sport' => 'required|string|max:255',
            'best_record' => 'nullable|string|max:1000',
            'introduction' => 'required|string|min:100|max:5000',
            'training_experience' => 'required|string|min:100|max:5000',
            'motivation' => 'required|string|min:50|max:1000',
            'headline' => 'required|string|max:255',
            'photo' => 'required|file|image|max:5120', // 5MB max, must be an image
            'video_url' => 'nullable|url|max:1000', // Optional video URL
            'monthly_price_egp' => 'required|numeric|min:0',
            'instapay_link' => 'required|url|max:1000',
            'certifications' => 'nullable|array',
            'certifications.*.certificate_name' => 'required_with:certifications|string|max:255',
            'certifications.*.certificate_image' => 'required_with:certifications|file|mimes:jpg,jpeg,png|max:5120', // 5MB max, must be an image
            'certifications.*.year' => 'required_with:certifications|integer|min:1900|max:' . (date('Y') + 1),
          
        ];
    }
}
