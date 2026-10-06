<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mock_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('test_code')->unique();
            $table->integer('total_questions')->default(20);
            $table->integer('score')->default(0);
            $table->decimal('percentage', 5, 2)->default(0.00);
            $table->enum('result', ['pass', 'fail'])->nullable();
            $table->integer('time_taken_seconds')->default(0);
            $table->json('question_answers')->nullable(); // stored answers json
            $table->timestamp('taken_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mock_tests');
    }
};
