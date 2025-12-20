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

            // Core identity - CURP is the primary identifier
            $table->string('curp', 18)->unique()->charset('ascii')->collation('ascii_general_ci');
            $table->string('rfc', 13)->nullable()->charset('ascii')->collation('ascii_general_ci');

            // Personal information
            $table->string('nombres', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('apellido_paterno', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('apellido_materno', 255)->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('telefono_movil', 20)->charset('ascii')->collation('ascii_general_ci');

            // Demographics
            $table->string('nacionalidad', 100)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->enum('sexo', ['masculino', 'femenino'])->charset('ascii')->collation('ascii_general_ci');
            $table->date('fecha_nacimiento');
            $table->string('pais_nacimiento', 100)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('estado_nacimiento', 100)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');

            // Address
            $table->string('estado', 100)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('municipio', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('localidad', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('codigo_postal', 5)->charset('ascii')->collation('ascii_general_ci');
            $table->string('calle', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('numero_exterior', 20)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('numero_interior', 20)->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');

            // Verification status
            $table->enum('curp_verification_status', ['pending', 'verified', 'failed'])->default('pending')->charset('ascii')->collation('ascii_general_ci');
            $table->enum('face_verification_status', ['pending', 'verified', 'failed'])->default('pending')->charset('ascii')->collation('ascii_general_ci');
            $table->enum('account_status', ['active', 'pending_verification', 'suspended', 'inactive'])->default('pending_verification')->charset('ascii')->collation('ascii_general_ci');

            // Verification timestamps
            $table->timestamp('curp_verified_at')->nullable();
            $table->timestamp('face_verified_at')->nullable();

            // Face verification data
            $table->decimal('face_verification_confidence', 5, 2)->nullable();

            // Audit fields
            $table->json('verification_metadata')->nullable();
            $table->string('registration_ip', 45)->nullable()->charset('ascii')->collation('ascii_general_ci');
            $table->text('registration_user_agent')->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');

            $table->timestamps();

            // Optimized indexes
            $table->index(['curp'], 'idx_users_curp');
            $table->index(['curp_verification_status'], 'idx_curp_status');
            $table->index(['account_status'], 'idx_account_status');
            $table->index(['created_at'], 'idx_users_created_at');

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
