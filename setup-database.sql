-- Maritime Transport Medicine Database Setup
-- This script creates the database and user for full connectivity

-- Create the database
CREATE DATABASE IF NOT EXISTS maritime_transport_medicine 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

-- Create the user
CREATE USER IF NOT EXISTS 'maritime_admin'@'localhost' 
    IDENTIFIED BY 'Maritime2024!';

-- Grant all privileges
GRANT ALL PRIVILEGES ON maritime_transport_medicine.* 
    TO 'maritime_admin'@'localhost';

-- Refresh privileges
FLUSH PRIVILEGES;

-- Verify the setup
SELECT 'Database created successfully' as status;
SELECT User, Host FROM mysql.user WHERE User = 'maritime_admin';
SHOW DATABASES LIKE 'maritime_transport_medicine';