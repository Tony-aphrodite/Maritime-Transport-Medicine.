<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('doctor_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
            $table->timestamp('hold_expires_at')->nullable()->after('status'); // For tracking the 15-min timer
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
            $table->dropColumn(['doctor_id', 'hold_expires_at']);
        });
    }
};
