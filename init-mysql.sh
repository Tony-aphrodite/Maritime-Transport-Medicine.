#!/bin/bash

echo "🚀 Setting up MySQL for Maritime Transport Medicine..."

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${YELLOW}Note: This script requires MySQL root access to set up the database and user.${NC}"
echo "Please enter the MySQL root password when prompted."
echo ""

# Try to execute the MySQL setup
if mysql -u root -p < setup-mysql-auth.sql; then
    echo -e "${GREEN}✅ MySQL database and user created successfully!${NC}"
    
    # Test the connection
    echo "Testing Laravel database connection..."
    php artisan config:clear
    
    if php artisan migrate:status > /dev/null 2>&1; then
        echo -e "${GREEN}✅ Laravel can connect to MySQL successfully!${NC}"
        echo ""
        echo "Database Configuration:"
        echo "  Database: maritime_transport_db"
        echo "  User: laravel_user"
        echo "  Password: laravel_password"
        echo ""
    else
        echo -e "${RED}❌ Laravel cannot connect to MySQL. Checking connection...${NC}"
        php artisan migrate:status
    fi
else
    echo -e "${RED}❌ Failed to set up MySQL database.${NC}"
    echo "This might be because:"
    echo "1. MySQL root password is required"
    echo "2. MySQL service is not running"
    echo "3. Permissions issue"
    echo ""
    echo "Please ensure MySQL is running and you have root access."
    exit 1
fi