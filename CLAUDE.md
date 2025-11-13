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
- `app/Models/` - Eloquent models (currently has User model)
- `app/Http/Controllers/` - Controllers (currently using route closures)
- `resources/views/` - Blade templates (login.blade.php, registro.blade.php, dashboard.blade.php, hello.blade.php)
- `routes/web.php` - Web routes (simple view routes for auth flow)
- `database/migrations/` - Database schema migrations
- `resources/css/` and `resources/js/` - Frontend assets compiled by Vite

### Current Implementation
The application currently uses simple route closures in `routes/web.php` that return views directly:
- `/` - Redirects to login
- `/login` - Login view
- `/registro` - Registration view  
- `/dashboard` - Dashboard view
- `/hello` - Hello view

### Authentication
- Uses Laravel's built-in authentication with User model
- Sanctum is included for API token authentication
- Standard Laravel password reset functionality available

### Frontend Stack
- Vite for asset compilation
- CSS and JavaScript processing through Laravel Vite plugin
- Input files: `resources/css/app.css`, `resources/js/app.js`

## Key Configuration Files
- `composer.json` - PHP dependencies and autoloading
- `package.json` - Node.js dependencies and scripts
- `vite.config.js` - Vite configuration for asset compilation
- `phpunit.xml` - PHPUnit testing configuration
- `.env` - Environment configuration (not in repository)