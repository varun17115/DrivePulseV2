<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->string('service_type');
            $table->text('description')->nullable();
            $table->decimal('cost', 10, 2)->default(0.00);
            $table->date('service_date');
            $table->date('next_service_due')->nullable();
            $table->string('service_center')->nullable();
            $table->integer('odometer_reading')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_services');
    }
};
