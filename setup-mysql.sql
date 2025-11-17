-- Maritime Transport Medicine MySQL Setup Script
-- This script sets up the MySQL database for the project

-- Create database
CREATE DATABASE IF NOT EXISTS maritime_transport_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

-- Use the database
USE maritime_transport_db;

-- Create user for the application (optional, for production)
-- CREATE USER IF NOT EXISTS 'maritime_user'@'localhost' IDENTIFIED BY 'secure_password_here';
-- GRANT ALL PRIVILEGES ON maritime_transport_db.* TO 'maritime_user'@'localhost';
-- FLUSH PRIVILEGES;

-- Set MySQL session variables for optimal performance
SET SESSION innodb_strict_mode = ON;
SET SESSION sql_mode = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';

-- Optimize MySQL settings for this database
ALTER DATABASE maritime_transport_db 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Show database status
SELECT 'Database maritime_transport_db created successfully' AS Status;
SHOW DATABASES LIKE 'maritime_transport_db';