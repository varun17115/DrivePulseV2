<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTrainerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $trainer = $this->route('trainer');
        $userId = $trainer ? $trainer->user_id : null;

        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'employee_id' => ['required', 'string', 'max:50', Rule::unique('trainers')->ignore($trainer ? $trainer->id : null)],
            'license_number' => ['required', 'string', 'max:50', Rule::unique('trainers')->ignore($trainer ? $trainer->id : null)],
            'license_expiry' => 'required|date',
            'experience_years' => 'required|integer|min:0',
            'specialization' => 'nullable|string|max:255',
            'hourly_rate' => 'required|numeric|min:0',
            'status' => ['required', Rule::in(['available', 'busy', 'on_leave', 'inactive'])],
        ];
    }
}
