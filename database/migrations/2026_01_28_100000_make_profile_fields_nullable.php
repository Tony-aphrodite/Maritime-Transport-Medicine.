<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Make profile fields nullable so users can register with just email/password
     * and complete their profile later.
     */
    public function up(): void
    {
        // For SQLite, we need to recreate the table since SQLite has limited ALTER TABLE support
        if (DB::connection()->getDriverName() === 'sqlite') {
            // Get current table structure
            DB::statement('PRAGMA foreign_keys=off;');

            // Create new table with nullable columns
            DB::statement('
                CREATE TABLE users_new (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    email VARCHAR(255) NOT NULL UNIQUE,
                    password VARCHAR(255) NOT NULL,
                    email_verified_at TIMESTAMP NULL,
                    remember_token VARCHAR(100) NULL,
                    name VARCHAR(255) NULL,
                    curp VARCHAR(18) NULL,
                    rfc VARCHAR(13) NULL,
                    nombres VARCHAR(255) NULL,
                    apellido_paterno VARCHAR(255) NULL,
                    apellido_materno VARCHAR(255) NULL,
                    telefono_movil VARCHAR(20) NULL,
                    telefono_casa VARCHAR(20) NULL,
                    nacionalidad VARCHAR(100) NULL,
                    sexo VARCHAR(20) NULL,
                    fecha_nacimiento DATE NULL,
                    pais_nacimiento VARCHAR(100) NULL,
                    estado_nacimiento VARCHAR(100) NULL,
                    estado VARCHAR(100) NULL,
                    municipio VARCHAR(255) NULL,
                    localidad VARCHAR(255) NULL,
                    codigo_postal VARCHAR(5) NULL,
                    calle VARCHAR(255) NULL,
                    numero_exterior VARCHAR(20) NULL,
                    numero_interior VARCHAR(20) NULL,
                    curp_verification_status VARCHAR(20) DEFAULT "pending",
                    face_verification_status VARCHAR(20) DEFAULT "pending",
                    account_status VARCHAR(30) DEFAULT "pending_verification",
                    profile_completed BOOLEAN DEFAULT 0,
                    curp_verified_at TIMESTAMP NULL,
                    face_verified_at TIMESTAMP NULL,
                    face_verification_confidence DECIMAL(5,2) NULL,
                    verification_metadata TEXT NULL,
                    registration_ip VARCHAR(45) NULL,
                    registration_user_agent TEXT NULL,
                    ine_front_path VARCHAR(255) NULL,
                    ine_back_path VARCHAR(255) NULL,
                    passport_path VARCHAR(255) NULL,
                    selfie_path VARCHAR(255) NULL,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL
                )
            ');

            // Copy data from old table if exists
            $columns = [
                'id', 'email', 'password', 'email_verified_at', 'remember_token', 'name',
                'curp', 'rfc', 'nombres', 'apellido_paterno', 'apellido_materno',
                'telefono_movil', 'telefono_casa', 'nacionalidad', 'sexo', 'fecha_nacimiento',
                'pais_nacimiento', 'estado_nacimiento', 'estado', 'municipio', 'localidad',
                'codigo_postal', 'calle', 'numero_exterior', 'numero_interior',
                'curp_verification_status', 'face_verification_status', 'account_status',
                'profile_completed', 'curp_verified_at', 'face_verified_at',
                'face_verification_confidence', 'verification_metadata',
                'registration_ip', 'registration_user_agent',
                'ine_front_path', 'ine_back_path', 'passport_path', 'selfie_path',
                'created_at', 'updated_at'
            ];

            // Get existing columns in the old table
            $existingColumns = collect(DB::select("PRAGMA table_info(users)"))->pluck('name')->toArray();
            $commonColumns = array_intersect($columns, $existingColumns);
            $columnList = implode(', ', $commonColumns);

            if (!empty($commonColumns)) {
                DB::statement("INSERT INTO users_new ($columnList) SELECT $columnList FROM users");
            }

            // Drop old table and rename new one
            DB::statement('DROP TABLE users');
            DB::statement('ALTER TABLE users_new RENAME TO users');

            // Create index on curp if column exists
            DB::statement('CREATE INDEX IF NOT EXISTS idx_users_curp ON users(curp)');
            DB::statement('CREATE INDEX IF NOT EXISTS idx_users_email ON users(email)');

            DB::statement('PRAGMA foreign_keys=on;');
        } else {
            // For MySQL, we can use ALTER TABLE
            Schema::table('users', function (Blueprint $table) {
                $table->string('curp', 18)->nullable()->change();
                $table->string('nombres', 255)->nullable()->change();
                $table->string('apellido_paterno', 255)->nullable()->change();
                $table->string('telefono_movil', 20)->nullable()->change();
                $table->string('nacionalidad', 100)->nullable()->change();
                $table->string('sexo', 20)->nullable()->change();
                $table->date('fecha_nacimiento')->nullable()->change();
                $table->string('pais_nacimiento', 100)->nullable()->change();
                $table->string('estado_nacimiento', 100)->nullable()->change();
                $table->string('estado', 100)->nullable()->change();
                $table->string('municipio', 255)->nullable()->change();
                $table->string('localidad', 255)->nullable()->change();
                $table->string('codigo_postal', 5)->nullable()->change();
                $table->string('calle', 255)->nullable()->change();
                $table->string('numero_exterior', 20)->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: Reversing this migration would require making columns NOT NULL
        // which could fail if there's data with NULL values
        // For SQLite, this would need another table recreation
    }
};
