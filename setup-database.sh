#!/bin/bash

# Maritime Transport Medicine Database Setup Script
# This script sets up the MySQL database and runs migrations

echo "Setting up Maritime Transport Medicine Database..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if MySQL is running
print_status "Checking MySQL service..."
if ! systemctl is-active --quiet mysql; then
    print_warning "MySQL service is not running. Attempting to start..."
    sudo systemctl start mysql
    if [ $? -ne 0 ]; then
        print_error "Failed to start MySQL service"
        exit 1
    fi
fi

# Create database using SQL script
print_status "Creating MySQL database..."
mysql -u root -p < setup-mysql.sql
if [ $? -eq 0 ]; then
    print_status "Database created successfully"
else
    print_warning "Database creation failed or database already exists"
fi

# Clear Laravel configuration cache
print_status "Clearing Laravel configuration cache..."
php artisan config:clear

# Run Laravel migrations
print_status "Running database migrations..."
php artisan migrate --force

if [ $? -eq 0 ]; then
    print_status "Database setup completed successfully!"
    print_status "Database: maritime_transport_db"
    print_status "Connection: MySQL"
    print_status "You can now run: php artisan serve"
else
    print_error "Migration failed. Please check your database configuration."
    exit 1
fi

# Optional: Create sample data
read -p "Do you want to create sample audit log data? (y/n): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    print_status "Creating sample data..."
    php artisan tinker --execute="App\Models\AuditLog::createTestData();"
    print_status "Sample data created!"
fi

print_status "Setup complete! You can now run 'php artisan serve' to start the application."