<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@drivepulse.com'],
            [
                'name' => 'Alexander Vance',
                'password' => Hash::make('password'),
                'phone' => '+1 (555) 019-2831',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $admin->syncRoles(['admin']);

        // 2. Trainers
        $trainersData = [
            [
                'name' => 'Marcus Sterling',
                'email' => 'marcus.trainer@drivepulse.com',
                'phone' => '+1 (555) 839-2041',
                'employee_id' => 'TRN-2024-001',
                'license_number' => 'DL-TRN-884920',
                'license_expiry' => '2028-12-31',
                'experience_years' => 8,
                'specialization' => 'Manual Transmission & Defensive Driving',
                'hourly_rate' => 45.00,
                'status' => 'available',
            ],
            [
                'name' => 'Elena Rostova',
                'email' => 'elena.trainer@drivepulse.com',
                'phone' => '+1 (555) 720-4921',
                'employee_id' => 'TRN-2024-002',
                'license_number' => 'DL-TRN-932104',
                'license_expiry' => '2029-05-15',
                'experience_years' => 6,
                'specialization' => 'Automatic & Highway/Night Navigation',
                'hourly_rate' => 42.00,
                'status' => 'available',
            ],
            [
                'name' => 'David Kim',
                'email' => 'david.trainer@drivepulse.com',
                'phone' => '+1 (555) 619-3382',
                'employee_id' => 'TRN-2024-003',
                'license_number' => 'DL-TRN-741902',
                'license_expiry' => '2027-09-20',
                'experience_years' => 11,
                'specialization' => 'Advanced Parking, Roundabouts & Test Prep',
                'hourly_rate' => 50.00,
                'status' => 'available',
            ],
        ];

        foreach ($trainersData as $tData) {
            $user = User::firstOrCreate(
                ['email' => $tData['email']],
                [
                    'name' => $tData['name'],
                    'password' => Hash::make('password'),
                    'phone' => $tData['phone'],
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]
            );
            $user->syncRoles(['trainer']);

            Trainer::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'employee_id' => $tData['employee_id'],
                    'license_number' => $tData['license_number'],
                    'license_expiry' => $tData['license_expiry'],
                    'experience_years' => $tData['experience_years'],
                    'specialization' => $tData['specialization'],
                    'hourly_rate' => $tData['hourly_rate'],
                    'status' => $tData['status'],
                ]
            );
        }

        // 3. Students
        $studentsData = [
            [
                'name' => 'Sophia Martinez',
                'email' => 'sophia.student@drivepulse.com',
                'phone' => '+1 (555) 482-1940',
                'admission_number' => 'STU-2026-001',
                'dob' => '2004-06-14',
                'gender' => 'female',
                'address' => '402 Sunset Blvd, Springfield, OR',
                'emergency_contact' => '+1 (555) 482-1941',
                'emergency_contact_name' => 'Carlos Martinez (Father)',
                'license_type' => 'Class C - Manual',
                'enrollment_date' => '2026-08-01',
                'course_status' => 'in_progress',
                'total_lessons_booked' => 12,
                'lessons_completed' => 10,
                'total_amount' => 650.00,
                'paid_amount' => 650.00,
                'due_amount' => 0.00,
            ],
            [
                'name' => 'Liam Gallagher',
                'email' => 'liam.student@drivepulse.com',
                'phone' => '+1 (555) 391-8842',
                'admission_number' => 'STU-2026-002',
                'dob' => '2005-02-28',
                'gender' => 'male',
                'address' => '118 Pinecrest Ave, Springfield, OR',
                'emergency_contact' => '+1 (555) 391-8840',
                'emergency_contact_name' => 'Fiona Gallagher (Sister)',
                'license_type' => 'Class C - Automatic',
                'enrollment_date' => '2026-08-15',
                'course_status' => 'in_progress',
                'total_lessons_booked' => 8,
                'lessons_completed' => 6,
                'total_amount' => 650.00,
                'paid_amount' => 400.00,
                'due_amount' => 250.00,
            ],
            [
                'name' => 'Emily Chen',
                'email' => 'emily.student@drivepulse.com',
                'phone' => '+1 (555) 902-3114',
                'admission_number' => 'STU-2026-003',
                'dob' => '2003-11-09',
                'gender' => 'female',
                'address' => '78 River Road, Eugene, OR',
                'emergency_contact' => '+1 (555) 902-3100',
                'emergency_contact_name' => 'Wei Chen (Mother)',
                'license_type' => 'Class C - Manual',
                'enrollment_date' => '2026-07-10',
                'course_status' => 'completed',
                'total_lessons_booked' => 15,
                'lessons_completed' => 15,
                'total_amount' => 650.00,
                'paid_amount' => 650.00,
                'due_amount' => 0.00,
            ],
            [
                'name' => 'Noah Jenkins',
                'email' => 'noah.student@drivepulse.com',
                'phone' => '+1 (555) 671-9230',
                'admission_number' => 'STU-2026-004',
                'dob' => '2006-01-19',
                'gender' => 'male',
                'address' => '944 Cedar Way, Springfield, OR',
                'emergency_contact' => '+1 (555) 671-9200',
                'emergency_contact_name' => 'Sarah Jenkins (Mother)',
                'license_type' => 'Class C - Automatic',
                'enrollment_date' => '2026-09-01',
                'course_status' => 'enrolled',
                'total_lessons_booked' => 4,
                'lessons_completed' => 2,
                'total_amount' => 650.00,
                'paid_amount' => 250.00,
                'due_amount' => 400.00,
            ],
            [
                'name' => 'Aaliyah Patel',
                'email' => 'aaliyah.student@drivepulse.com',
                'phone' => '+1 (555) 542-8819',
                'admission_number' => 'STU-2026-005',
                'dob' => '2004-09-25',
                'gender' => 'female',
                'address' => '230 Oak Ridge Dr, Eugene, OR',
                'emergency_contact' => '+1 (555) 542-8800',
                'emergency_contact_name' => 'Raj Patel (Father)',
                'license_type' => 'Class C - Manual',
                'enrollment_date' => '2026-09-10',
                'course_status' => 'enrolled',
                'total_lessons_booked' => 2,
                'lessons_completed' => 0,
                'total_amount' => 650.00,
                'paid_amount' => 650.00,
                'due_amount' => 0.00,
            ],
        ];

        foreach ($studentsData as $sData) {
            $user = User::firstOrCreate(
                ['email' => $sData['email']],
                [
                    'name' => $sData['name'],
                    'password' => Hash::make('password'),
                    'phone' => $sData['phone'],
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]
            );
            $user->syncRoles(['student']);

            Student::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'admission_number' => $sData['admission_number'],
                    'dob' => $sData['dob'],
                    'gender' => $sData['gender'],
                    'address' => $sData['address'],
                    'emergency_contact' => $sData['emergency_contact'],
                    'emergency_contact_name' => $sData['emergency_contact_name'],
                    'license_type' => $sData['license_type'],
                    'enrollment_date' => $sData['enrollment_date'],
                    'course_status' => $sData['course_status'],
                    'total_lessons_booked' => $sData['total_lessons_booked'],
                    'lessons_completed' => $sData['lessons_completed'],
                    'total_amount' => $sData['total_amount'],
                    'paid_amount' => $sData['paid_amount'],
                    'due_amount' => $sData['due_amount'],
                ]
            );
        }
    }
}
