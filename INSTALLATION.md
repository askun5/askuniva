# Univa - Installation Guide

## Prerequisites

- PHP 8.2 or higher
- Composer
- MySQL 8.0 or higher (or PostgreSQL/SQLite)
- Node.js (optional, for asset compilation)

## Installation Steps

### 1. Install Laravel

If you haven't already, install a fresh Laravel installation:

```bash
cd "C:\Users\Hanjaya Chandra\Claude Projects\Univa\Laravel"
composer create-project laravel/laravel temp
```

Then move the Laravel files to the root:
```bash
# Move Laravel core files (except the ones we've created)
# Keep our custom files: app/, database/, routes/, resources/, public/css, public/js
```

Or simply copy our custom files into a fresh Laravel installation.

### 2. Configure Environment

Copy the environment file and generate an app key:

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Configure Database

Edit `.env` with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=univa
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Create the Database

```sql
CREATE DATABASE univa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Run Migrations and Seeders

```bash
php artisan migrate
php artisan db:seed
```

This will create:
- All database tables
- An admin user (admin@univa.com / password)
- Default site settings
- Default pages (About, Privacy, Terms)
- Grade-specific guidelines

### 6. Create Storage Link

```bash
php artisan storage:link
```

This allows uploaded images (logo, hero background) to be publicly accessible.

### 7. Start the Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` to see the application.

## Default Credentials

### Admin Login
- Email: `admin@univa.com`
- Password: `password`

Access the admin panel at: `/admin`

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/           # Admin panel controllers
│   │   ├── Portal/          # Student portal controllers
│   │   ├── AuthController.php
│   │   ├── ContactController.php
│   │   ├── HomeController.php
│   │   └── PageController.php
│   └── Middleware/
│       └── AdminMiddleware.php
├── Models/
│   ├── User.php
│   ├── SiteSetting.php
│   ├── GradeGuideline.php
│   ├── Page.php
│   └── ContactSubmission.php

database/
├── migrations/              # Database schema
└── seeders/
    └── DatabaseSeeder.php   # Initial data

resources/views/
├── layouts/
│   ├── public.blade.php     # Public pages layout
│   ├── portal.blade.php     # Student portal layout
│   └── admin.blade.php      # Admin panel layout
├── public/                  # Front page, contact, etc.
├── auth/                    # Authentication views
├── portal/                  # Student dashboard views
└── admin/                   # Admin panel views

routes/
└── web.php                  # All application routes
```

## Routes Overview

### Public Routes
- `/` - Home page
- `/about` - About page
- `/privacy` - Privacy policy
- `/terms` - Terms of service
- `/contact` - Contact form

### Authentication Routes
- `/get-started` - Sign up
- `/signin` - Sign in
- `/forgot-password` - Password reset request
- `/reset-password/{token}` - Password reset form

### Student Portal (requires auth)
- `/portal` - Dashboard
- `/portal/guidelines` - Grade-specific guidelines
- `/portal/advisor` - AI Advisor (Chatfuel integration)
- `/portal/profile` - User profile

### Admin Panel (requires admin role)
- `/admin` - Admin dashboard
- `/admin/branding` - Logo and favicon management
- `/admin/content/homepage` - Hero section editor
- `/admin/content/pages` - Pages management
- `/admin/content/footer` - Footer links editor
- `/admin/guidelines` - Grade guidelines editor
- `/admin/contacts` - Contact submissions

## Chatfuel Integration

To integrate your Chatfuel AI agent:

1. Log in to the admin panel
2. The Chatfuel Bot ID can be configured in the database or added to the admin settings
3. Update `resources/views/portal/advisor.blade.php` with your actual Chatfuel embed code

The AI Advisor page passes user context (grade, name) to Chatfuel for personalized responses.

## Customization

### Changing the Hero Background

1. Log in as admin
2. Go to Admin > Homepage
3. Upload a new background image (recommended: 1920x1080px)

### Updating the Logo

1. Log in as admin
2. Go to Admin > Branding
3. Upload your logo (PNG or SVG recommended)

### Editing Grade Guidelines

1. Log in as admin
2. Go to Admin > Guidelines
3. Click "Edit" for each grade level
4. Use the rich text editor to format content

## Security Notes

1. Change the default admin password immediately after installation
2. Configure proper HTTPS in production
3. Set appropriate file permissions
4. Review and update `.env` for production settings
