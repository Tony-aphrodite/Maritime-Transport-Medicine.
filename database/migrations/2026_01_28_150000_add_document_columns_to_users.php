<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add document_path and document_type columns to users table
     */
    public function up(): void
    {
        // For SQLite, we need to check if columns exist and add them
        if (DB::connection()->getDriverName() === 'sqlite') {
            // Check existing columns
            $existingColumns = collect(DB::select("PRAGMA table_info(users)"))->pluck('name')->toArray();

            // Add document_path if not exists
            if (!in_array('document_path', $existingColumns)) {
                DB::statement('ALTER TABLE users ADD COLUMN document_path VARCHAR(255) NULL');
            }

            // Add document_type if not exists
            if (!in_array('document_type', $existingColumns)) {
                DB::statement('ALTER TABLE users ADD COLUMN document_type VARCHAR(50) NULL');
            }
        } else {
            // For MySQL
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'document_path')) {
                    $table->string('document_path', 255)->nullable();
                }
                if (!Schema::hasColumn('users', 'document_type')) {
                    $table->string('document_type', 50)->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['document_path', 'document_type']);
        });
    }
};
