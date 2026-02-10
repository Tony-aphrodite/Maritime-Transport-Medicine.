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
        Schema::create('zonas_horarias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);           // Display name (e.g., "Zona Central / Ciudad de Mexico")
            $table->string('codigo', 50);            // PHP timezone code (e.g., "America/Mexico_City")
            $table->string('offset', 20);            // GMT offset display (e.g., "GMT-6")
            $table->integer('offset_minutos')->default(0); // Offset in minutes from UTC
            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0);    // Display order
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zonas_horarias');
    }
};
