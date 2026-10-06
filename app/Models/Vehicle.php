<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_number',
        'make',
        'model',
        'year',
        'transmission_type',
        'fuel_type',
        'status',
        'insurance_expiry',
        'fitness_certificate_expiry',
        'pollution_check_expiry',
        'current_odometer',
    ];

    protected function casts(): array
    {
        return [
            'insurance_expiry' => 'date',
            'fitness_certificate_expiry' => 'date',
            'pollution_check_expiry' => 'date',
        ];
    }

    public function services(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(VehicleService::class);
    }

    public function bookings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
