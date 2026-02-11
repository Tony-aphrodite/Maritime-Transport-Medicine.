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
        Schema::table('appointment_documents', function (Blueprint $table) {
            // Add fields for doctor review functionality
            $table->text('notes')->nullable()->after('status');
            $table->timestamp('reviewed_at')->nullable()->after('notes');
            $table->foreignId('reviewed_by')->nullable()->after('reviewed_at')
                ->constrained('users')->onDelete('set null');

            // Add index for efficient querying
            $table->index('status');
            $table->index('reviewed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointment_documents', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropIndex(['status']);
            $table->dropIndex(['reviewed_at']);
            $table->dropColumn(['notes', 'reviewed_at', 'reviewed_by']);
        });
    }
};
