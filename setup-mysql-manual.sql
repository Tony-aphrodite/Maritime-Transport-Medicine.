-- MySQL Setup for Maritime Transport Medicine
-- Run this script as MySQL admin: sudo mysql < setup-mysql-manual.sql

-- Create the database with proper charset
CREATE DATABASE IF NOT EXISTS maritime_transport_medicine 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Create a user for Laravel (compatible with MySQL 8.0)
CREATE USER IF NOT EXISTS 'maritime_admin'@'localhost' IDENTIFIED WITH mysql_native_password BY 'Maritime2024!';

-- Grant full privileges to the user
GRANT ALL PRIVILEGES ON maritime_transport_medicine.* TO 'maritime_admin'@'localhost';

-- Also grant privileges for Laravel operations
GRANT CREATE, ALTER, DROP, INDEX, REFERENCES ON maritime_transport_medicine.* TO 'maritime_admin'@'localhost';

-- Apply the changes
FLUSH PRIVILEGES;

-- Show confirmation
SELECT 'Maritime Transport Medicine database created successfully!' as Status;
USE maritime_transport_medicine;
SHOW TABLES;