<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Appointment Date/Time
            $table->date('appointment_date');
            $table->string('appointment_time', 10);
            $table->string('timezone', 50)->default('America/Mexico_City');

            // Medical Declaration
            $table->string('exam_type', 20); // new, renewal
            $table->integer('years_at_sea')->default(0);
            $table->string('current_position', 100)->nullable();
            $table->string('vessel_type', 100)->nullable();

            // Health Information
            $table->boolean('has_chronic_conditions')->default(false);
            $table->text('chronic_conditions_detail')->nullable();
            $table->boolean('takes_medications')->default(false);
            $table->text('medications_detail')->nullable();
            $table->boolean('has_allergies')->default(false);
            $table->text('allergies_detail')->nullable();
            $table->boolean('has_surgeries')->default(false);
            $table->text('surgeries_detail')->nullable();
            $table->json('workplace_risks')->nullable();
            $table->text('additional_notes')->nullable();

            // Payment Information
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);

            // Status
            $table->string('status', 30)->default('pending_payment');
            $table->string('payment_status', 30)->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->string('payment_reference', 100)->nullable();
            $table->string('payment_method', 50)->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'status']);
            $table->index('appointment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
