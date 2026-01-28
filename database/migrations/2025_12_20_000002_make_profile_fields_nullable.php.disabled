<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Make profile fields nullable for the new registration flow
     * where users only provide email/password initially and complete profile later.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Make all profile fields nullable for new registration flow
            $table->string('rfc', 13)->nullable()->change();
            $table->string('nombres', 255)->nullable()->change();
            $table->string('apellido_paterno', 255)->nullable()->change();
            $table->string('apellido_materno', 255)->nullable()->change();
            $table->string('telefono_movil', 20)->nullable()->change();
            $table->string('nacionalidad', 100)->nullable()->change();
            $table->enum('sexo', ['masculino', 'femenino'])->nullable()->change();
            $table->date('fecha_nacimiento')->nullable()->change();
            $table->string('pais_nacimiento', 100)->nullable()->change();
            $table->string('estado_nacimiento', 100)->nullable()->change();
            $table->string('estado', 100)->nullable()->change();
            $table->string('municipio', 255)->nullable()->change();
            $table->string('localidad', 255)->nullable()->change();
            $table->string('codigo_postal', 5)->nullable()->change();
            $table->string('calle', 255)->nullable()->change();
            $table->string('numero_exterior', 20)->nullable()->change();
            $table->string('numero_interior', 20)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: Reversing nullable changes could cause data loss if there are null values
        // This is intentionally left minimal
        Schema::table('users', function (Blueprint $table) {
            // Revert to NOT NULL (requires data cleanup first)
        });
    }
};
