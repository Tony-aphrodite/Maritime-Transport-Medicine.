<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointment_holds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->date('appointment_date');
            $table->time('appointment_time'); // UTC time
            $table->timestamp('expires_at');  // When the 15-minute hold expires
            $table->string('session_id', 255)->nullable(); // To identify user session
            $table->timestamps();

            // Unique constraint: one hold per slot per doctor
            $table->unique(['doctor_id', 'appointment_date', 'appointment_time'], 'unique_hold_slot');

            // Index for cleanup of expired holds
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_holds');
    }
};
