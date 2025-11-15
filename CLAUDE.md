# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 10 web application for Maritime Transport Medicine. The project uses PHP 8.1+ and includes basic authentication flows with views for login, registration, and dashboard pages.

## Development Commands

### Core Laravel Commands
- **Start development server**: `php artisan serve` (serves on http://localhost:8000)
- **Run database migrations**: `php artisan migrate`
- **Generate application key**: `php artisan key:generate`
- **Clear application cache**: `php artisan cache:clear`
- **Clear config cache**: `php artisan config:clear`
- **Clear route cache**: `php artisan route:clear`

### Frontend Development
- **Start Vite dev server**: `npm run dev` (hot reloading for CSS/JS)
- **Build for production**: `npm run build`
- **Install Node dependencies**: `npm install`

### PHP Development
- **Install Composer dependencies**: `composer install`
- **Update Composer dependencies**: `composer update`
- **Run code formatting**: `./vendor/bin/pint` (Laravel Pint for PSR-12 formatting)

### Testing
- **Run all tests**: `php artisan test` or `./vendor/bin/phpunit`
- **Run specific test**: `php artisan test tests/Feature/ExampleTest.php`
- **Run tests with coverage**: `./vendor/bin/phpunit --coverage-html coverage`

## Architecture

### Directory Structure
- `app/Models/` - Eloquent models (User, AuditLog)
- `app/Http/Controllers/` - Controllers (Login, Registration, Admin, CURP, FaceVerification)
- `resources/views/` - Blade templates with admin subdirectory for admin panel
- `routes/web.php` - Web routes with proper controller-based routing
- `database/migrations/` - Database schema migrations
- `resources/css/` and `resources/js/` - Frontend assets compiled by Vite

### Key Features
The application has evolved from simple view routes to a feature-rich system:
- **User Registration/Login**: Full authentication flow with LoginController and RegistrationController
- **CURP Validation**: Mexican ID validation using VerificaMex API integration
- **Face Verification**: Facial recognition for identity verification 
- **Admin Panel**: Complete admin dashboard with audit logging and statistics
- **Audit System**: Comprehensive logging system tracking all user interactions

### Routing Structure
- Authentication routes (`/login`, `/registro`) handled by dedicated controllers
- CURP validation routes (`/curp/validate`) with API integration
- Face verification routes (`/face-verification/*`) for biometric validation
- Admin routes (`/admin/*`) with authentication middleware and dashboard
- API endpoints for admin statistics and audit log data

### Authentication
- Uses Laravel's built-in authentication with User model
- Sanctum is included for API token authentication
- Standard Laravel password reset functionality available

### Frontend Stack
- Vite for asset compilation
- CSS and JavaScript processing through Laravel Vite plugin
- Input files: `resources/css/app.css`, `resources/js/app.js`

## CURP Validation Feature

### Overview
Complete CURP (Clave Única de Registro de Población) validation system integrated with VerificaMex API for real-time validation against RENAPO database.

### Components
- **Standalone Validation Page**: `/curp/validate` - Dedicated CURP validation interface
- **Registration Integration**: Enhanced CURP input in registration form with real-time validation
- **Backend API**: `CurpController` handles VerificaMex API integration
- **Client-side Validation**: JavaScript for format validation and UX

### API Configuration
Configure VerificaMex API credentials in `.env`:
```
VERIFICAMEX_TOKEN=your-bearer-token-here
VERIFICAMEX_BASE_URL=https://api.verificamex.com/v1
```

## Admin System

### Overview
Comprehensive admin panel for monitoring user registrations, verification processes, and system health.

### Admin Authentication
- Admin credentials are hardcoded in AdminController (for development)
- Session-based authentication for admin access
- Default credentials: `AdminJuan@gmail.com` / `johnson@suceess!`

### Admin Features
- **Dashboard**: Real-time statistics and analytics
- **Audit Logs**: Complete event tracking with filtering and export
- **User Monitoring**: Track registration flows and verification status
- **Data Export**: CSV export functionality for audit logs

### Admin Routes
- `GET /admin/dashboard` - Main admin dashboard with statistics
- `GET /admin/audit-logs` - Audit log viewer with advanced filtering
- `GET /admin/audit-logs/export` - Export audit logs to CSV
- `GET /admin/api/dashboard-stats` - JSON API for dashboard statistics
- `GET /admin/api/audit-logs-data` - JSON API for audit log data (DataTables compatible)

## Audit System

### AuditLog Model
Central logging system tracking all user interactions and system events:

### Event Types
- Registration events (started, completed, failed)
- CURP verification attempts and results
- Face verification with confidence scores
- Account creation events
- Admin access and authentication events

### Audit Data Structure
Each audit log captures:
- Event type and status
- User identifier and session data
- IP address and user agent
- Request details (method, URL)
- Custom event data (JSON)
- Verification IDs and confidence scores

### Routes
- `GET /curp/validate` - Show CURP validation form
- `POST /curp/validate` - Submit CURP for validation
- `POST /curp/validate-format` - AJAX format validation

### CURP Format
18-character alphanumeric code following official Mexican CURP structure:
- Example: `PEGJ850415HDFRRN05`
- Validation includes format checking and RENAPO database verification

## Face Verification System

### Overview
Biometric facial recognition system for identity verification using face matching technology.

### Components
- **Face Verification Controller**: Handles image comparison and verification logic
- **Client-side Integration**: Camera access and image capture functionality
- **Confidence Scoring**: Returns match confidence percentages
- **Audit Integration**: All verification attempts logged in audit system

### Routes
- `GET /face-verification` - Face verification interface
- `POST /face-verification/compare` - Compare uploaded images
- `GET /face-verification/status` - Check verification status

## Development Notes

### Database Considerations
- Application includes graceful handling for database unavailability
- Audit system falls back to Laravel logs if database connection fails
- Admin dashboard shows appropriate messaging when database is offline
- Use `php artisan migrate` to set up required database tables

### Security Considerations
- Admin credentials are hardcoded for development (should be moved to database in production)
- CURP data is partially masked in audit logs for privacy
- All API integrations include proper error handling and logging
- Session-based admin authentication with proper logout functionality

### Testing Routes
The application includes several test routes for development:
- `/admin/test-credentials` - Shows expected admin login credentials
- `/admin/create-test-data` - Generates sample audit log data
- `/admin/admin-status` - Shows current admin authentication status

## Key Configuration Files
- `composer.json` - PHP dependencies and autoloading
- `package.json` - Node.js dependencies and scripts  
- `vite.config.js` - Vite configuration for asset compilation
- `phpunit.xml` - PHPUnit testing configuration
- `.env` - Environment configuration (not in repository)