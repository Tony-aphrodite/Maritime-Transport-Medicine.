# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 10 web application for Maritime Transport Medicine. The project uses PHP 8.1+ and includes advanced identity verification systems including CURP (Mexican ID) validation, facial recognition, and comprehensive audit logging for regulatory compliance.

## Development Commands

### Core Laravel Commands
- **Start development server**: `php artisan serve` (serves on http://localhost:8000)
- **Run database migrations**: `php artisan migrate`
- **Generate application key**: `php artisan key:generate`
- **Clear application cache**: `php artisan cache:clear`
- **Clear config cache**: `php artisan config:clear`
- **Clear route cache**: `php artisan route:clear`

### Database Setup
- **Setup MySQL database**: Various SQL scripts available in root directory for database initialization
- **Create database user**: `create_mysql_user.php` script for user creation
- **Alternative database setup**: Multiple `.sql` files for different setup scenarios
- **Note**: Database connection issues are gracefully handled - application works without database for basic functionality

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
- `app/Models/` - Eloquent models (User, AuditLog, ParentalConsent)
- `app/Http/Controllers/` - Controllers (Login, Registration, Admin, CURP, FaceVerification, ParentalConsent)
- `resources/views/` - Blade templates with admin subdirectory for admin panel
- `routes/web.php` - Web routes with proper controller-based routing
- `database/migrations/` - Database schema migrations
- `resources/css/` and `resources/js/` - Frontend assets compiled by Vite

### Key Features
The application has evolved from simple view routes to a feature-rich system:
- **User Registration/Login**: Full authentication flow with LoginController and RegistrationController
- **CURP Validation**: Mexican ID validation using VerificaMex API integration
- **Face Verification**: Facial recognition for identity verification 
- **Parental Consent**: Token-based consent system for minor verification compliance
- **Admin Panel**: Complete admin dashboard with audit logging and statistics
- **Audit System**: Comprehensive logging system tracking all user interactions

### Routing Structure
- **Authentication Routes**: `/login`, `/registro` handled by LoginController and RegistrationController
- **CURP Validation Routes**: `/curp/validate` with VerificaMex API integration via CurpController
- **Face Verification Routes**: `/face-verification/*` for biometric validation via FaceVerificationController
- **Parental Consent Routes**: `/parental-consent/*` for handling minor verification consent via ParentalConsentController
- **Admin Routes**: `/admin/*` with session-based authentication and comprehensive dashboard
- **Admin API Routes**: `/admin/api/*` for dashboard statistics, audit logs, and real-time monitoring

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
- **Backend API**: `CurpController` handles VerificaMex API integration with comprehensive error handling
- **Client-side Validation**: JavaScript for format validation and UX improvements

### API Configuration
Configure VerificaMex API credentials in `.env`:
```env
VERIFICAMEX_TOKEN=your-bearer-token-here
VERIFICAMEX_BASE_URL=https://api.verificamex.com
```

### Routes
- `GET /curp/validate` - Show CURP validation form
- `POST /curp/validate` - Submit CURP for validation
- `POST /curp/validate-format` - AJAX format validation

### CURP Format
18-character alphanumeric code following official Mexican CURP structure:
- Example: `PEGJ850415HDFRRN05`
- Validation includes format checking and RENAPO database verification

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
- Parental consent requests and approvals
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

## Face Verification System

### Overview
Biometric facial recognition system for identity verification using face matching technology with secure AWS S3 image storage.

### Components
- **Face Verification Controller**: Handles image comparison and verification logic
- **AWS S3 Integration**: Secure image upload with temporary URLs for verification
- **Client-side Integration**: Camera access and image capture functionality
- **Confidence Scoring**: Returns match confidence percentages
- **Audit Integration**: All verification attempts logged in audit system
- **Automatic Cleanup**: S3 images are automatically deleted after verification

### Security Features
- **Secure Upload**: Images uploaded to private S3 bucket before verification
- **Temporary URLs**: Pre-signed URLs with 1-hour expiration for verification API calls
- **Automatic Cleanup**: Images deleted from S3 after verification completion
- **Organized Storage**: Images stored in dated directories with unique verification IDs

### Routes
- `GET /face-verification` - Face verification interface
- `POST /face-verification/compare` - Upload images to S3 and compare via API
- `GET /face-verification/status` - Check verification status

### S3 Storage Structure
```
face-verification/
├── 2025/11/22/
│   ├── face_abc123/
│   │   ├── selfie.jpg
│   │   └── ine.jpg
│   └── face_def456/
│       ├── selfie.png
│       └── ine.png
```

## Parental Consent System

### Overview
Token-based parental consent system for handling verification of minors, ensuring compliance with age verification requirements.

### Components
- **ParentalConsent Model**: Tracks consent requests, approvals, and status
- **Token-based Authentication**: Secure token system for parent verification
- **Status Tracking**: Real-time consent status monitoring
- **Audit Integration**: All consent activities logged in audit system

### Routes
- `GET /parental-consent/approve/{token}` - Show consent form to parents
- `POST /parental-consent/approve/{token}` - Process parental consent decision
- `GET /parental-consent/status/{token}` - Check consent status via API

### Workflow
1. System generates unique token for minor's registration
2. Parent receives secure link with token
3. Parent reviews information and provides consent
4. System updates consent status and continues verification process

## Development Notes

### Database Considerations
- Application includes graceful handling for database unavailability
- Audit system falls back to Laravel logs if database connection fails
- Admin dashboard shows appropriate messaging when database is offline
- Use `php artisan migrate` to set up required database tables

### Database Models
- **User**: Standard Laravel user model with authentication capabilities
- **AuditLog**: Central logging model tracking all system events and user interactions
- **ParentalConsent**: Manages consent tokens, status, and approval workflow for minors

### Security Considerations
- Admin credentials are hardcoded for development (should be moved to database in production)
- CURP data is partially masked in audit logs for privacy
- All API integrations include proper error handling and logging
- Session-based admin authentication with proper logout functionality

### Environment Configuration
Essential environment variables for external APIs and services:
```env
# VerificaMex CURP API
VERIFICAMEX_TOKEN=your-verificamex-token
VERIFICAMEX_BASE_URL=https://api.verificamex.com

# Face Verification API (if implemented)  
FACE_VERIFY_TOKEN=your-face-verification-token
FACE_VERIFY_BASE_URL=https://api.facecompare.com
FACE_VERIFY_ENDPOINT=/v1/compare

# AWS S3 Configuration (for secure image storage)
AWS_ACCESS_KEY_ID=your-aws-access-key
AWS_SECRET_ACCESS_KEY=your-aws-secret-key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-s3-bucket-name
FILESYSTEM_DISK=s3

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=your-database-password
```

**Important**: Copy `.env.example` to `.env` and update with your actual credentials. Never commit API tokens to version control.

### Testing and Development Routes
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