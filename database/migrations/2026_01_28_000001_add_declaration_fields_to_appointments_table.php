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
        Schema::table('appointments', function (Blueprint $table) {
            // Declaration checkboxes
            $table->boolean('declaration_truthful')->default(false)->after('additional_notes');
            $table->boolean('declaration_terms')->default(false)->after('declaration_truthful');
            $table->boolean('declaration_privacy')->default(false)->after('declaration_terms');
            $table->boolean('declaration_consent')->default(false)->after('declaration_privacy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn([
                'declaration_truthful',
                'declaration_terms',
                'declaration_privacy',
                'declaration_consent',
            ]);
        });
    }
};
