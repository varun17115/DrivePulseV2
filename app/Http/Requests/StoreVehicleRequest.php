<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'registration_number' => ['required', 'string', 'max:50', 'unique:vehicles,registration_number'],
            'make' => ['required', 'string', 'max:50'],
            'model' => ['required', 'string', 'max:50'],
            'year' => ['required', 'integer', 'min:1990', 'max:' . (date('Y') + 1)],
            'transmission_type' => ['required', 'in:manual,automatic'],
            'fuel_type' => ['required', 'in:petrol,diesel,electric,hybrid'],
            'status' => ['required', 'in:active,in_service,out_of_order,retired'],
            'insurance_expiry' => ['nullable', 'date'],
            'fitness_certificate_expiry' => ['nullable', 'date'],
            'pollution_check_expiry' => ['nullable', 'date'],
            'current_odometer' => ['required', 'integer', 'min:0'],
        ];
    }
}
