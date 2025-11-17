# MySQL Database Setup Instructions

## Issue
The admin audit-logs page shows "Base de datos no disponible" because the MySQL database user doesn't exist.

## Quick Fix

### Option 1: Manual MySQL Setup

1. **Access MySQL as root**:
   ```bash
   sudo mysql
   ```

2. **Create database and user**:
   ```sql
   CREATE DATABASE IF NOT EXISTS maritime_transport_db 
   CHARACTER SET utf8mb4 
   COLLATE utf8mb4_unicode_ci;

   CREATE USER IF NOT EXISTS 'laravel_user'@'localhost' 
   IDENTIFIED BY 'laravel_password';

   GRANT ALL PRIVILEGES ON maritime_transport_db.* 
   TO 'laravel_user'@'localhost';

   FLUSH PRIVILEGES;
   EXIT;
   ```

3. **Run Laravel migrations**:
   ```bash
   php artisan migrate
   ```

### Option 2: Using Setup Script

1. **Run the setup script** (if you have MySQL root access):
   ```bash
   sudo mysql < setup.sql
   ```

2. **Run Laravel migrations**:
   ```bash
   php artisan migrate
   ```

## Testing the Fix

1. **Test database connection**:
   ```bash
   php artisan tinker --execute="DB::connection()->getPdo(); echo 'Connected!'"
   ```

2. **Access admin panel**:
   - Go to `/login`
   - Use admin credentials: `AdminJuan@gmail.com` / `johnson@suceess!`
   - Navigate to "Audit Logs" page
   - The warning message should disappear and real data should show

## Expected Result

✅ **Before Fix**: Page shows "Base de datos no disponible" warning
✅ **After Fix**: Page shows real audit log statistics and data table

## Alternative: Use SQLite (Temporary)

If MySQL setup is problematic, temporarily use SQLite by updating `.env`:

```env
DB_CONNECTION=sqlite
DB_DATABASE=/full/path/to/database.sqlite
```

Then:
```bash
touch database/database.sqlite
php artisan migrate
```

This will allow the audit logs to work with a file-based database instead of MySQL.