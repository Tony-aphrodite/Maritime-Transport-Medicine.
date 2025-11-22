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
            $table->string('minor_email', 320)->charset('utf8mb4')->collation('utf8mb4_unicode_ci'); // RFC 5321 max email length
            $table->string('minor_full_name', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci'); // Full name of the minor
            $table->date('minor_birth_date'); // Birth date of the minor
            $table->string('parent_full_name', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci'); // Full name of parent/guardian
            $table->string('parent_email', 320)->charset('utf8mb4')->collation('utf8mb4_unicode_ci'); // Parent/guardian email
            $table->string('parent_phone', 20)->nullable()->charset('ascii')->collation('ascii_general_ci'); // Parent phone number - ASCII sufficient for phones
            $table->enum('relationship', ['parent', 'guardian', 'tutor'])->default('parent')->charset('ascii')->collation('ascii_general_ci'); // Enum for better performance
            $table->string('consent_token', 64)->unique()->charset('ascii')->collation('ascii_general_ci'); // Fixed length token for better performance
            $table->enum('status', ['pending', 'approved', 'denied', 'expired'])->default('pending')->charset('ascii')->collation('ascii_general_ci');
            $table->timestamp('consent_requested_at'); // When consent was requested
            $table->timestamp('consent_given_at')->nullable(); // When consent was given
            $table->timestamp('expires_at'); // When the consent request expires
            $table->json('consent_data')->nullable(); // MySQL native JSON for additional consent data
            $table->string('ip_address', 45)->nullable()->charset('ascii')->collation('ascii_general_ci'); // IPv4 or IPv6 address
            $table->text('user_agent')->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci'); // Browser info of consent giver
            $table->text('digital_signature')->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci'); // Digital signature or confirmation
            $table->text('terms_accepted')->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci'); // Specific terms accepted
            $table->timestamps();
            
            // MySQL-optimized indexes for performance
            $table->index(['minor_email', 'status'], 'idx_minor_email_status');
            $table->index(['parent_email', 'status'], 'idx_parent_email_status');
            $table->index(['status', 'expires_at'], 'idx_status_expires');
            $table->index(['consent_token'], 'idx_consent_token');
            $table->index(['created_at'], 'idx_pc_created_at');
            
            // MySQL storage engine and charset
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
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
