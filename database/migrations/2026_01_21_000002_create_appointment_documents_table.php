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
        Schema::create('appointment_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('cascade');

            $table->string('document_type', 50); // identification, medical_history, sea_book, photo, other
            $table->string('original_name', 255);
            $table->string('file_path', 500);
            $table->bigInteger('file_size')->default(0);
            $table->string('mime_type', 100)->nullable();
            $table->string('status', 30)->default('uploaded');

            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'appointment_id']);
            $table->index('document_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_documents');
    }
};
