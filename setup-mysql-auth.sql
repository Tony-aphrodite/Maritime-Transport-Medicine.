-- MySQL Authentication Setup for Maritime Transport Medicine Project

-- Create database
CREATE DATABASE IF NOT EXISTS maritime_transport_db 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Create dedicated user for Laravel
CREATE USER IF NOT EXISTS 'laravel_user'@'localhost' 
IDENTIFIED BY 'laravel_password';

-- Grant all privileges on the database to the Laravel user
GRANT ALL PRIVILEGES ON maritime_transport_db.* 
TO 'laravel_user'@'localhost';

-- Grant additional privileges needed for Laravel
GRANT CREATE, ALTER, DROP, INSERT, UPDATE, DELETE, SELECT, REFERENCES, RELOAD 
ON *.* TO 'laravel_user'@'localhost';

-- Apply changes
FLUSH PRIVILEGES;

-- Show created database and user
SHOW DATABASES LIKE 'maritime_transport_db';
SELECT User, Host FROM mysql.user WHERE User = 'laravel_user';

-- Test connection
USE maritime_transport_db;
SELECT 'MySQL database setup completed successfully!' AS Status;