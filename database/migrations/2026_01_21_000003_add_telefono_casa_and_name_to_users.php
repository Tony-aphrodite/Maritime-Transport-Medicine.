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
            // Add name column if it doesn't exist
            if (!Schema::hasColumn('users', 'name')) {
                $table->string('name', 255)->nullable()->after('email_verified_at');
            }

            // Add telefono_casa column if it doesn't exist
            if (!Schema::hasColumn('users', 'telefono_casa')) {
                $table->string('telefono_casa', 20)->nullable()->after('telefono_movil');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'telefono_casa')) {
                $table->dropColumn('telefono_casa');
            }
            if (Schema::hasColumn('users', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
};
