-- =============================================
-- APPOINTMENT SCHEDULING SYSTEM - DATABASE SETUP
-- =============================================
-- Run this SQL to set up the appointment scheduling system
-- This includes: doctors, schedules, vacations, blockages, and holds

-- =============================================
-- 1. DOCTORS TABLE
-- =============================================
CREATE TABLE IF NOT EXISTS `doctors` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) UNIQUE NOT NULL,
    `phone` VARCHAR(20) NULL,
    `specialty` VARCHAR(100) DEFAULT 'Medicina del Transporte Maritimo',
    `license_number` VARCHAR(50) NULL COMMENT 'Cedula profesional',
    `timezone` VARCHAR(50) DEFAULT 'America/Mexico_City',
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 2. DOCTOR SCHEDULES TABLE
-- =============================================
CREATE TABLE IF NOT EXISTS `doctor_schedules` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `doctor_id` BIGINT UNSIGNED NOT NULL,
    `day_of_week` TINYINT NOT NULL COMMENT '0=Sunday, 1=Monday, ..., 6=Saturday',
    `start_time` TIME NOT NULL COMMENT 'UTC time',
    `end_time` TIME NOT NULL COMMENT 'UTC time',
    `slot_duration` INT DEFAULT 60 COMMENT 'Minutes per slot',
    `max_appointments_per_slot` INT DEFAULT 1 COMMENT 'Max patients per slot',
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `doctor_schedules_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
    UNIQUE KEY `doctor_schedules_doctor_day_unique` (`doctor_id`, `day_of_week`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 3. DOCTOR VACATIONS TABLE
-- =============================================
CREATE TABLE IF NOT EXISTS `doctor_vacations` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `doctor_id` BIGINT UNSIGNED NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `reason` VARCHAR(255) NULL COMMENT 'vacation, conference, sick leave, etc.',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `doctor_vacations_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
    INDEX `doctor_vacations_date_range_index` (`doctor_id`, `start_date`, `end_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 4. DOCTOR BLOCKAGES TABLE
-- =============================================
CREATE TABLE IF NOT EXISTS `doctor_blockages` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `doctor_id` BIGINT UNSIGNED NOT NULL,
    `blocked_date` DATE NOT NULL,
    `start_time` TIME NULL COMMENT 'If null, entire day is blocked',
    `end_time` TIME NULL COMMENT 'If null, entire day is blocked',
    `reason` VARCHAR(255) NULL COMMENT 'meeting, emergency, etc.',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `doctor_blockages_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
    INDEX `doctor_blockages_date_index` (`doctor_id`, `blocked_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 5. APPOINTMENT HOLDS TABLE (15-minute timer)
-- =============================================
CREATE TABLE IF NOT EXISTS `appointment_holds` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `doctor_id` BIGINT UNSIGNED NOT NULL,
    `appointment_date` DATE NOT NULL,
    `appointment_time` TIME NOT NULL COMMENT 'UTC time',
    `expires_at` TIMESTAMP NOT NULL COMMENT 'When the 15-minute hold expires',
    `session_id` VARCHAR(255) NULL COMMENT 'To identify user session',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `appointment_holds_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `appointment_holds_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_hold_slot` (`doctor_id`, `appointment_date`, `appointment_time`),
    INDEX `appointment_holds_expires_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 6. ADD DOCTOR_ID TO APPOINTMENTS TABLE
-- =============================================
ALTER TABLE `appointments`
ADD COLUMN IF NOT EXISTS `doctor_id` BIGINT UNSIGNED NULL AFTER `user_id`,
ADD COLUMN IF NOT EXISTS `hold_expires_at` TIMESTAMP NULL AFTER `status`;

-- Add foreign key (only if not exists)
-- Note: You may need to run this separately if the constraint already exists
-- ALTER TABLE `appointments` ADD CONSTRAINT `appointments_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL;

-- =============================================
-- 7. INSERT SAMPLE DOCTOR DATA
-- =============================================
INSERT INTO `doctors` (`name`, `email`, `phone`, `specialty`, `license_number`, `timezone`, `is_active`, `created_at`, `updated_at`)
VALUES
('Dr. Juan Carlos Martinez', 'dr.martinez@latitudmedica.com', '+52 999 123 4567', 'Medicina del Transporte Maritimo', 'CED-12345678', 'America/Mexico_City', 1, NOW(), NOW());

-- Get the inserted doctor ID
SET @doctor_id = LAST_INSERT_ID();

-- =============================================
-- 8. INSERT SAMPLE SCHEDULE (Monday to Saturday, 9am-5pm UTC)
-- =============================================
INSERT INTO `doctor_schedules` (`doctor_id`, `day_of_week`, `start_time`, `end_time`, `slot_duration`, `max_appointments_per_slot`, `is_active`, `created_at`, `updated_at`)
VALUES
(@doctor_id, 1, '15:00:00', '23:00:00', 60, 1, 1, NOW(), NOW()),  -- Monday (9am-5pm Mexico = 15:00-23:00 UTC)
(@doctor_id, 2, '15:00:00', '23:00:00', 60, 1, 1, NOW(), NOW()),  -- Tuesday
(@doctor_id, 3, '15:00:00', '23:00:00', 60, 1, 1, NOW(), NOW()),  -- Wednesday
(@doctor_id, 4, '15:00:00', '23:00:00', 60, 1, 1, NOW(), NOW()),  -- Thursday
(@doctor_id, 5, '15:00:00', '23:00:00', 60, 1, 1, NOW(), NOW()),  -- Friday
(@doctor_id, 6, '15:00:00', '20:00:00', 60, 1, 1, NOW(), NOW());  -- Saturday (9am-2pm Mexico)

-- =============================================
-- NOTES:
-- =============================================
-- 1. Times are stored in UTC for consistency
-- 2. Mexico City is UTC-6, so 9am Mexico = 15:00 UTC
-- 3. slot_duration is in minutes (60 = 1 hour slots)
-- 4. max_appointments_per_slot = 1 means only one patient per time slot
-- 5. To block a specific time, add a row to doctor_blockages
-- 6. To add vacation, add a row to doctor_vacations
-- 7. The 15-minute hold is automatically managed by appointment_holds table
