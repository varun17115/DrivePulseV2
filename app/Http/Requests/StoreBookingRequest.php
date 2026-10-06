<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'trainer_id' => ['required', 'exists:trainers,id'],
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'lesson_type' => ['required', 'in:theory,practical,simulator,mock_test'],
            'status' => ['required', 'in:pending,confirmed,completed,cancelled,no_show'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
