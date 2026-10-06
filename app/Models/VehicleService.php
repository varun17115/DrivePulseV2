<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleService extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'service_type',
        'description',
        'cost',
        'service_date',
        'next_service_due',
        'service_center',
        'odometer_reading',
    ];

    protected function casts(): array
    {
        return [
            'cost' => 'decimal:2',
            'service_date' => 'date',
            'next_service_due' => 'date',
        ];
    }

    public function vehicle(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
