<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions
        $permissions = [
            // Student permissions
            'student.view', 'student.create', 'student.edit', 'student.delete',
            // Trainer permissions
            'trainer.view', 'trainer.create', 'trainer.edit', 'trainer.delete',
            // Vehicle permissions
            'vehicle.view', 'vehicle.create', 'vehicle.edit', 'vehicle.delete',
            // Booking permissions
            'booking.view', 'booking.create', 'booking.edit', 'booking.cancel', 'booking.approve',
            // Attendance permissions
            'attendance.view', 'attendance.mark',
            // Progress permissions
            'progress.view', 'progress.evaluate',
            // Payment permissions
            'payment.view', 'payment.create', 'payment.invoice',
            // Mock test permissions
            'mocktest.take', 'mocktest.view', 'mocktest.manage',
            // Certificate permissions
            'certificate.view', 'certificate.generate', 'certificate.verify',
            // Settings & Reports
            'settings.manage', 'reports.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 1. Admin Role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // 2. Trainer Role
        $trainerRole = Role::firstOrCreate(['name' => 'trainer']);
        $trainerRole->givePermissionTo([
            'booking.view', 'booking.edit',
            'attendance.view', 'attendance.mark',
            'progress.view', 'progress.evaluate',
            'vehicle.view',
            'student.view',
        ]);

        // 3. Student Role
        $studentRole = Role::firstOrCreate(['name' => 'student']);
        $studentRole->givePermissionTo([
            'booking.view', 'booking.create', 'booking.cancel',
            'attendance.view',
            'progress.view',
            'payment.view',
            'mocktest.take', 'mocktest.view',
            'certificate.view',
        ]);
    }
}
