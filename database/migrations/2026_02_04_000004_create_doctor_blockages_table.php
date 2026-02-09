<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_blockages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->date('blocked_date');
            $table->time('start_time')->nullable(); // If null, entire day is blocked
            $table->time('end_time')->nullable();   // If null, entire day is blocked
            $table->string('reason', 255)->nullable(); // meeting, emergency, etc.
            $table->timestamps();

            // Index for quick lookups
            $table->index(['doctor_id', 'blocked_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_blockages');
    }
};
