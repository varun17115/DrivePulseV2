<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('employee_id')->unique();
            $table->string('license_number')->unique();
            $table->date('license_expiry');
            $table->integer('experience_years')->default(0);
            $table->string('specialization')->nullable();
            $table->decimal('hourly_rate', 8, 2)->default(0.00);
            $table->enum('status', ['available', 'busy', 'on_leave', 'inactive'])->default('available');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainers');
    }
};
