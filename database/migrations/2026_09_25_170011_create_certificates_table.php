<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('certificate_number')->unique();
            $table->string('course_name')->default('Comprehensive Driving Course');
            $table->date('issue_date');
            $table->date('completion_date');
            $table->string('grade')->default('A');
            $table->string('qr_code_hash')->unique();
            $table->string('file_path')->nullable();
            $table->enum('status', ['issued', 'revoked'])->default('issued');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
