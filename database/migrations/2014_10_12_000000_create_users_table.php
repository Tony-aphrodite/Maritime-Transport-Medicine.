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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('email', 320)->unique()->charset('utf8mb4')->collation('utf8mb4_unicode_ci'); // RFC 5321 max email length
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255); // For hashed passwords
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
            
            // MySQL-specific optimizations
            $table->index('email', 'idx_users_email');
            $table->index('created_at', 'idx_users_created_at');
            
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
        Schema::dropIfExists('users');
    }
};
