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
        Schema::table('users', function (Blueprint $table) {
            // Document fields for INE/Passport
            if (!Schema::hasColumn('users', 'document_path')) {
                $table->string('document_path', 500)->nullable()->after('profile_completed');
            }
            if (!Schema::hasColumn('users', 'document_type')) {
                $table->string('document_type', 20)->nullable()->after('document_path'); // 'ine' or 'passport'
            }

            // Profile photo field
            if (!Schema::hasColumn('users', 'profile_photo')) {
                $table->string('profile_photo', 500)->nullable()->after('document_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'document_path')) {
                $table->dropColumn('document_path');
            }
            if (Schema::hasColumn('users', 'document_type')) {
                $table->dropColumn('document_type');
            }
            if (Schema::hasColumn('users', 'profile_photo')) {
                $table->dropColumn('profile_photo');
            }
        });
    }
};
