# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 10 application called "Maritime Transport Medicine" - a user registration and verification system with CURP validation, face verification, and admin management capabilities. The application uses MySQL as the database and includes comprehensive audit logging.

## Common Development Commands

### PHP/Laravel Commands
- `php artisan serve` - Start development server
- `php artisan migrate` - Run database migrations
- `php artisan migrate:fresh --seed` - Reset and seed database
- `php artisan tinker` - Laravel REPL
- `composer install` - Install PHP dependencies
- `composer update` - Update PHP dependencies
- `vendor/bin/phpunit` - Run PHPUnit tests

### Frontend Commands
- `npm install` - Install Node.js dependencies
- `npm run dev` - Start Vite development server
- `npm run build` - Build assets for production

## Architecture Overview

### Core Components

**Controllers:**
- `LoginController` - Handles user authentication and admin login
- `RegistrationController` - User registration with CURP/face verification
- `CurpController` - CURP validation using VerificaMex API
- `FaceVerificationController` - Face comparison verification
- `AdminController` - Admin dashboard, audit logs, and user management
- `ParentalConsentController` - Parental consent workflow for minors

**Models:**
- `User` - User accounts with extensive profile data
- `AuditLog` - Comprehensive audit trail for all system actions
- `ParentalConsent` - Parental consent records with token-based approval

**Key Features:**
- CURP validation integration with VerificaMex API
- Face verification using external API
- Comprehensive audit logging system
- Admin dashboard with real-time statistics
- Parental consent workflow for minors
- Multi-step registration process with verification

### Database Structure

- Users table with extensive profile fields (CURP, address, phone, etc.)
- Audit logs table for security and compliance tracking
- Standard Laravel authentication tables

### External Integrations

**VerificaMex CURP API:**
- Base URL: `VERIFICAMEX_BASE_URL`
- Token: `VERIFICAMEX_TOKEN`
- Used for validating Mexican CURP numbers

**Face Verification API:**
- Base URL: `FACE_VERIFY_BASE_URL`
- Token: `FACE_VERIFY_TOKEN`
- Used for comparing user selfies with official documents

### Route Structure

- `/` - Redirects to login
- `/login` - User/admin authentication
- `/registro` - User registration form
- `/curp/validate` - CURP validation interface
- `/face-verification` - Face verification interface
- `/admin/*` - Admin panel routes (dashboard, users, audit logs)
- `/parental-consent/*` - Parental consent workflow

### Development Notes

- Uses MySQL with InnoDB engine and utf8mb4 charset
- Extensive validation on registration form (18+ fields)
- Session-based authentication for admin users
- Test routes available in admin section (should be removed in production)
- Face verification status checked during registration
- Audit logging captures all significant user actions

### Environment Configuration

Key environment variables:
- Database: `DB_*` settings for MySQL
- VerificaMex: `VERIFICAMEX_TOKEN`, `VERIFICAMEX_BASE_URL`
- Face API: `FACE_VERIFY_TOKEN`, `FACE_VERIFY_BASE_URL`
- AWS S3: `AWS_*` for file storage (configured but usage unclear)

### Security Considerations

- CSRF protection enabled
- Password confirmation required
- Audit logging for compliance
- Admin authentication separate from user auth
- Parental consent required for minors
- Face verification as additional security layer