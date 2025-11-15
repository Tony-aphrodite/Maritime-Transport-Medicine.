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
            $table->string('event_type', 100); // Event type (registration_started, curp_verification, etc.)
            $table->string('user_id', 50)->nullable(); // User identifier (CURP, email, etc.)
            $table->string('status', 50); // success, failure, pending, etc.
            $table->string('ip_address', 45); // IPv4 or IPv6 address
            $table->text('user_agent')->nullable(); // Browser/device information
            $table->text('event_data')->nullable(); // JSON data with additional event details
            $table->string('session_id', 100)->nullable(); // Session identifier
            $table->string('request_method', 10)->nullable(); // GET, POST, etc.
            $table->string('request_url', 500)->nullable(); // URL that triggered the event
            $table->text('error_message')->nullable(); // Error details if status is failure
            $table->string('verification_id', 100)->nullable(); // Links to verification attempts
            $table->decimal('confidence_score', 5, 2)->nullable(); // For face/identity verification
            $table->timestamps(); // created_at serves as timestamp
            
            // Indexes for performance
            $table->index(['event_type', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index(['status', 'created_at']);
            $table->index(['ip_address', 'created_at']);
            $table->index('verification_id');
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
