<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use App\Models\VehicleService;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = [
            [
                'registration_number' => 'OR-DP-101',
                'make' => 'Toyota',
                'model' => 'Corolla Dual-Control',
                'year' => 2023,
                'transmission_type' => 'manual',
                'fuel_type' => 'petrol',
                'status' => 'active',
                'insurance_expiry' => '2027-04-15',
                'fitness_certificate_expiry' => '2027-04-01',
                'pollution_check_expiry' => '2027-03-20',
                'current_odometer' => 24500,
            ],
            [
                'registration_number' => 'OR-DP-102',
                'make' => 'Honda',
                'model' => 'Civic Dual-Control',
                'year' => 2024,
                'transmission_type' => 'manual',
                'fuel_type' => 'petrol',
                'status' => 'active',
                'insurance_expiry' => '2027-08-30',
                'fitness_certificate_expiry' => '2027-08-15',
                'pollution_check_expiry' => '2027-07-25',
                'current_odometer' => 14200,
            ],
            [
                'registration_number' => 'OR-DP-201',
                'make' => 'Hyundai',
                'model' => 'Elantra Auto-Assist',
                'year' => 2024,
                'transmission_type' => 'automatic',
                'fuel_type' => 'hybrid',
                'status' => 'active',
                'insurance_expiry' => '2027-06-10',
                'fitness_certificate_expiry' => '2027-06-01',
                'pollution_check_expiry' => '2027-05-18',
                'current_odometer' => 18900,
            ],
            [
                'registration_number' => 'OR-DP-202',
                'make' => 'Nissan',
                'model' => 'Sentra Dual-Control',
                'year' => 2022,
                'transmission_type' => 'automatic',
                'fuel_type' => 'petrol',
                'status' => 'in_service',
                'insurance_expiry' => '2026-11-20',
                'fitness_certificate_expiry' => '2026-11-10',
                'pollution_check_expiry' => '2026-10-30',
                'current_odometer' => 38700,
            ],
        ];

        foreach ($vehicles as $vData) {
            $vehicle = Vehicle::firstOrCreate(
                ['registration_number' => $vData['registration_number']],
                $vData
            );

            // Add sample service record
            VehicleService::firstOrCreate(
                ['vehicle_id' => $vehicle->id, 'service_type' => 'Periodic Brake & Clutch Inspection'],
                [
                    'description' => 'Replaced front brake pads, adjusted dual-control clutch mechanism and refilled brake fluid.',
                    'cost' => 280.00,
                    'service_date' => '2026-07-15',
                    'next_service_due' => '2026-10-15',
                    'service_center' => 'Springfield Auto Care & Diagnostics',
                    'odometer_reading' => $vehicle->current_odometer - 2000,
                ]
            );
        }
    }
}
