-- Run this script as MySQL root: mysql -u root -p < setup.sql
-- Or run manually: sudo mysql < setup.sql

CREATE DATABASE IF NOT EXISTS maritime_transport_db 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

CREATE USER IF NOT EXISTS 'laravel_user'@'localhost' 
IDENTIFIED BY 'laravel_password';

GRANT ALL PRIVILEGES ON maritime_transport_db.* 
TO 'laravel_user'@'localhost';

FLUSH PRIVILEGES;

SELECT 'Database setup completed!' AS Status;
SHOW DATABASES LIKE 'maritime_transport_db';