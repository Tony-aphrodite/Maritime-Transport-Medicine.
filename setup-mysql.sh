#!/bin/bash
# MySQL Setup Script for Maritime Transport Medicine Project
# This script needs to be run with appropriate MySQL privileges

echo "Setting up MySQL for Maritime Transport Medicine Project..."

# Database and user configuration
DB_NAME="maritime_transport_medicine"
DB_USER="maritime_user"
DB_PASS="Maritime2024!"
DB_HOST="localhost"

echo "Creating MySQL database and user..."

# Try different MySQL connection methods
mysql_commands="
-- Create database with proper charset for international characters
CREATE DATABASE IF NOT EXISTS ${DB_NAME} 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Create dedicated user for this project
CREATE USER IF NOT EXISTS '${DB_USER}'@'${DB_HOST}' 
IDENTIFIED BY '${DB_PASS}';

-- Grant necessary privileges for Laravel
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES 
ON ${DB_NAME}.* 
TO '${DB_USER}'@'${DB_HOST}';

-- Grant additional privileges needed for migrations
GRANT REFERENCES ON ${DB_NAME}.* TO '${DB_USER}'@'${DB_HOST}';

-- Ensure privileges are applied
FLUSH PRIVILEGES;

-- Show created database
USE ${DB_NAME};
SHOW TABLES;
SELECT 'Database setup completed successfully!' as Status;
"

# Try to execute with different authentication methods
echo "Attempting to connect to MySQL..."

# Method 1: Try with sudo (Ubuntu default)
if sudo mysql -e "$mysql_commands" 2>/dev/null; then
    echo "✅ MySQL setup completed using sudo mysql"
    exit 0
fi

# Method 2: Try with root user and no password
if mysql -u root -e "$mysql_commands" 2>/dev/null; then
    echo "✅ MySQL setup completed using root user"
    exit 0
fi

# Method 3: Try with current user
if mysql -u $(whoami) -e "$mysql_commands" 2>/dev/null; then
    echo "✅ MySQL setup completed using current user"
    exit 0
fi

echo "❌ Could not connect to MySQL automatically."
echo ""
echo "Please run one of these commands manually:"
echo ""
echo "Option 1 - If you have sudo access:"
echo "sudo mysql"
echo ""
echo "Option 2 - If you know the root password:"
echo "mysql -u root -p"
echo ""
echo "Then execute these commands:"
echo "$mysql_commands"
echo ""
echo "After that, run: php artisan migrate"