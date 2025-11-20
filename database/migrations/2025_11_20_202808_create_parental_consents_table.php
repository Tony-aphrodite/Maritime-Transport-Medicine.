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
        Schema::create('parental_consents', function (Blueprint $table) {
            $table->id();
            $table->string('minor_email')->index(); // Email of the minor registering
            $table->string('minor_full_name'); // Full name of the minor
            $table->date('minor_birth_date'); // Birth date of the minor
            $table->string('parent_full_name'); // Full name of parent/guardian
            $table->string('parent_email')->index(); // Parent/guardian email
            $table->string('parent_phone')->nullable(); // Parent phone number
            $table->string('relationship')->default('parent'); // parent, guardian, etc.
            $table->string('consent_token')->unique(); // Unique token for consent verification
            $table->enum('status', ['pending', 'approved', 'denied', 'expired'])->default('pending');
            $table->timestamp('consent_requested_at'); // When consent was requested
            $table->timestamp('consent_given_at')->nullable(); // When consent was given
            $table->timestamp('expires_at'); // When the consent request expires
            $table->json('consent_data')->nullable(); // Additional consent data
            $table->string('ip_address')->nullable(); // IP address of consent giver
            $table->text('user_agent')->nullable(); // Browser info of consent giver
            $table->text('digital_signature')->nullable(); // Digital signature or confirmation
            $table->text('terms_accepted')->nullable(); // Specific terms accepted
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parental_consents');
    }
};
