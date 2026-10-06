<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTrainerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'employee_id' => 'required|string|max:50|unique:trainers',
            'license_number' => 'required|string|max:50|unique:trainers',
            'license_expiry' => 'required|date',
            'experience_years' => 'required|integer|min:0',
            'specialization' => 'nullable|string|max:255',
            'hourly_rate' => 'required|numeric|min:0',
            'status' => ['required', Rule::in(['available', 'busy', 'on_leave', 'inactive'])],
        ];
    }
}
