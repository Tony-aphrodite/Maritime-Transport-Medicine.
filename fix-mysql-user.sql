-- Fix MySQL User for Maritime Transport Medicine

-- Drop user if exists to start fresh
DROP USER IF EXISTS 'maritime_admin'@'localhost';

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS maritime_transport_medicine
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- Create the user with password
CREATE USER 'maritime_admin'@'localhost' IDENTIFIED BY 'Maritime2024!';

-- Grant all privileges
GRANT ALL PRIVILEGES ON maritime_transport_medicine.* TO 'maritime_admin'@'localhost';

-- Flush privileges
FLUSH PRIVILEGES;

-- Verify
SELECT user, host FROM mysql.user WHERE user = 'maritime_admin';
SHOW GRANTS FOR 'maritime_admin'@'localhost';
