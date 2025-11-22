-- MySQL Database Setup for Maritime Transport Medicine
-- Complete audit logging and real-time notifications system

-- Create the main database
CREATE DATABASE IF NOT EXISTS maritime_transport_medicine 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

-- Create the application user
CREATE USER IF NOT EXISTS 'maritime_admin'@'localhost' 
    IDENTIFIED BY 'Maritime2024!';

-- Grant comprehensive privileges for Laravel operations
GRANT ALL PRIVILEGES ON maritime_transport_medicine.* TO 'maritime_admin'@'localhost';
GRANT CREATE, ALTER, DROP, INDEX, REFERENCES ON maritime_transport_medicine.* TO 'maritime_admin'@'localhost';

-- Apply privileges
FLUSH PRIVILEGES;

-- Use the database
USE maritime_transport_medicine;

-- Show confirmation
SELECT 'Maritime Transport Medicine database created successfully!' AS Status;
SELECT USER() AS Connected_As;
SELECT DATABASE() AS Using_Database;

-- Show user privileges
SHOW GRANTS FOR 'maritime_admin'@'localhost';