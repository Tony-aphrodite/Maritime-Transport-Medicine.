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
            // Identity verification fields
            $table->string('curp', 18)->nullable()->unique()->charset('ascii')->collation('ascii_general_ci')->after('email'); // Mexican CURP
            $table->string('phone_number', 20)->nullable()->charset('ascii')->collation('ascii_general_ci')->after('curp'); // Phone number
            $table->date('birth_date')->nullable()->after('phone_number'); // Birth date
            $table->enum('gender', ['M', 'F', 'O'])->nullable()->charset('ascii')->collation('ascii_general_ci')->after('birth_date'); // Gender
            
            // Verification status fields
            $table->enum('curp_verification_status', ['pending', 'verified', 'failed', 'not_required'])->default('pending')->charset('ascii')->collation('ascii_general_ci')->after('gender');
            $table->enum('face_verification_status', ['pending', 'verified', 'failed', 'not_required'])->default('pending')->charset('ascii')->collation('ascii_general_ci')->after('curp_verification_status');
            $table->enum('document_verification_status', ['pending', 'verified', 'failed', 'not_required'])->default('pending')->charset('ascii')->collation('ascii_general_ci')->after('face_verification_status');
            $table->enum('account_status', ['active', 'pending_verification', 'suspended', 'inactive'])->default('pending_verification')->charset('ascii')->collation('ascii_general_ci')->after('document_verification_status');
            
            // Maritime transport specific fields
            $table->string('maritime_license_number', 50)->nullable()->charset('ascii')->collation('ascii_general_ci')->after('account_status'); // Maritime license
            $table->string('vessel_name', 255)->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->after('maritime_license_number'); // Associated vessel
            $table->string('company_name', 255)->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->after('vessel_name'); // Company/employer
            $table->enum('user_type', ['individual', 'company', 'maritime_professional', 'medical_professional'])->default('individual')->charset('ascii')->collation('ascii_general_ci')->after('company_name');
            
            // Verification timestamps
            $table->timestamp('curp_verified_at')->nullable()->after('user_type');
            $table->timestamp('face_verified_at')->nullable()->after('curp_verified_at');
            $table->timestamp('documents_verified_at')->nullable()->after('face_verified_at');
            $table->timestamp('last_verification_attempt')->nullable()->after('documents_verified_at');
            
            // Audit and security fields
            $table->json('verification_metadata')->nullable()->after('last_verification_attempt'); // Additional verification data
            $table->string('registration_ip', 45)->nullable()->charset('ascii')->collation('ascii_general_ci')->after('verification_metadata'); // IP at registration
            $table->text('registration_user_agent')->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->after('registration_ip'); // User agent at registration
            
            // MySQL-optimized indexes for performance
            $table->index(['curp'], 'idx_users_curp');
            $table->index(['curp_verification_status', 'created_at'], 'idx_curp_status_created');
            $table->index(['account_status', 'created_at'], 'idx_account_status_created');
            $table->index(['user_type', 'account_status'], 'idx_user_type_status');
            $table->index(['phone_number'], 'idx_users_phone');
            $table->index(['maritime_license_number'], 'idx_maritime_license');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex('idx_users_curp');
            $table->dropIndex('idx_curp_status_created');
            $table->dropIndex('idx_account_status_created');
            $table->dropIndex('idx_user_type_status');
            $table->dropIndex('idx_users_phone');
            $table->dropIndex('idx_maritime_license');
            
            // Drop columns in reverse order
            $table->dropColumn([
                'registration_user_agent',
                'registration_ip',
                'verification_metadata',
                'last_verification_attempt',
                'documents_verified_at',
                'face_verified_at',
                'curp_verified_at',
                'user_type',
                'company_name',
                'vessel_name',
                'maritime_license_number',
                'account_status',
                'document_verification_status',
                'face_verification_status',
                'curp_verification_status',
                'gender',
                'birth_date',
                'phone_number',
                'curp'
            ]);
        });
    }
};
