-- MySQL Database Setup Script for Maritime Transport Medicine
-- Create database and user with appropriate permissions

-- Create the database
CREATE DATABASE IF NOT EXISTS maritime_transport_medicine CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user and grant permissions
CREATE USER IF NOT EXISTS 'maritime_user'@'localhost' IDENTIFIED BY 'secure_password_123';
GRANT ALL PRIVILEGES ON maritime_transport_medicine.* TO 'maritime_user'@'localhost';

-- Grant additional privileges for Laravel migrations
GRANT CREATE, ALTER, DROP, INDEX ON maritime_transport_medicine.* TO 'maritime_user'@'localhost';

-- Flush privileges to ensure changes take effect
FLUSH PRIVILEGES;

-- Show created database
SHOW DATABASES LIKE 'maritime_transport_medicine';