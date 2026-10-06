<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('trainer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->string('skill_topic'); // Steering, Clutch Control, Parallel Parking, Highway Driving, Night Driving, Roundabouts, Reverse Parking, Hazard Perception
            $table->tinyInteger('score')->default(1); // 1 to 5
            $table->text('feedback')->nullable();
            $table->enum('competency_status', ['needs_practice', 'proficient', 'mastered'])->default('needs_practice');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress');
    }
};
