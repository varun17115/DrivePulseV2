<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'school_name', 'value' => 'DrivePulse Driving Academy', 'group' => 'company', 'type' => 'string', 'description' => 'Official Name of the Driving School'],
            ['key' => 'school_email', 'value' => 'contact@drivepulse.com', 'group' => 'company', 'type' => 'string', 'description' => 'Contact Email Address'],
            ['key' => 'school_phone', 'value' => '+1 (555) 234-5678', 'group' => 'company', 'type' => 'string', 'description' => 'Contact Phone Number'],
            ['key' => 'school_address', 'value' => '742 Evergreen Terrace, Springfield, OR 97477', 'group' => 'company', 'type' => 'string', 'description' => 'Physical Address'],
            ['key' => 'currency_symbol', 'value' => '₹', 'group' => 'payment', 'type' => 'string', 'description' => 'Currency symbol for payments'],
            ['key' => 'currency_code', 'value' => 'INR', 'group' => 'payment', 'type' => 'string', 'description' => 'Currency code (e.g. USD, EUR, INR)'],
            ['key' => 'standard_course_fee', 'value' => '650.00', 'group' => 'payment', 'type' => 'decimal', 'description' => 'Standard driving course package fee'],
            ['key' => 'mock_test_pass_percentage', 'value' => '80', 'group' => 'booking', 'type' => 'integer', 'description' => 'Minimum score percentage required to pass mock test'],
            ['key' => 'total_practical_lessons_required', 'value' => '15', 'group' => 'booking', 'type' => 'integer', 'description' => 'Total practical lessons required for course completion certificate'],
            ['key' => 'slot_duration_minutes', 'value' => '60', 'group' => 'booking', 'type' => 'integer', 'description' => 'Duration of each lesson slot in minutes'],
            ['key' => 'working_hours_start', 'value' => '08:00', 'group' => 'booking', 'type' => 'string', 'description' => 'Daily operational start time'],
            ['key' => 'working_hours_end', 'value' => '18:00', 'group' => 'booking', 'type' => 'string', 'description' => 'Daily operational end time'],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
