<?php

namespace Modules\Tasks\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Sæt til true, eller lav en policy/tjek om admin
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'admin_notification_email' => 'sometimes|nullable|email',
        ];
    }

    /**
     * Custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'admin_notification_email.email' => 'Indtast venligst en gyldig e-mailadresse til administratoren.',
        ];
    }
}
