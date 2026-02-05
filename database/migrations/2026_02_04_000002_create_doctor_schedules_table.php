<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('day_of_week'); // 0=Sunday, 1=Monday, ..., 6=Saturday
            $table->time('start_time'); // UTC time
            $table->time('end_time');   // UTC time
            $table->integer('slot_duration')->default(60); // minutes per slot
            $table->integer('max_appointments_per_slot')->default(1); // max patients per slot
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Unique constraint: one schedule per doctor per day
            $table->unique(['doctor_id', 'day_of_week']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_schedules');
    }
};
