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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event_type', 100)->charset('utf8mb4')->collation('utf8mb4_unicode_ci'); // Event type (registration_started, curp_verification, etc.)
            $table->string('user_id', 50)->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci'); // User identifier (CURP, email, etc.)
            $table->enum('status', ['success', 'failure', 'pending', 'in_progress', 'cancelled'])->default('pending'); // Optimized status enum
            $table->string('ip_address', 45)->charset('ascii')->collation('ascii_general_ci'); // IPv4 or IPv6 address - ASCII is sufficient
            $table->text('user_agent')->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci'); // Browser/device information
            $table->json('event_data')->nullable(); // JSON data with additional event details - MySQL native JSON
            $table->string('session_id', 100)->nullable()->charset('ascii')->collation('ascii_general_ci'); // Session identifier
            $table->enum('request_method', ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'])->nullable(); // HTTP methods enum
            $table->string('request_url', 1000)->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci'); // URL that triggered the event
            $table->text('error_message')->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci'); // Error details if status is failure
            $table->string('verification_id', 100)->nullable()->charset('ascii')->collation('ascii_general_ci'); // Links to verification attempts
            $table->decimal('confidence_score', 5, 2)->nullable(); // For face/identity verification
            $table->timestamps(); // created_at serves as timestamp
            
            // MySQL-optimized indexes for performance
            $table->index(['event_type', 'created_at'], 'idx_event_type_created');
            $table->index(['user_id', 'created_at'], 'idx_user_created');
            $table->index(['status', 'created_at'], 'idx_status_created');
            $table->index(['ip_address', 'created_at'], 'idx_ip_created');
            $table->index('verification_id', 'idx_verification_id');
            $table->index('created_at', 'idx_created_at'); // Additional index for date filtering
            
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
        Schema::dropIfExists('audit_logs');
    }
};
