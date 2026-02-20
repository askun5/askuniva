<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Portal\DashboardController;
use App\Http\Controllers\Portal\GuidelinesController;
use App\Http\Controllers\Portal\AdvisorController;
use App\Http\Controllers\Portal\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BrandingController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\GuidelinesManagementController;
use App\Http\Controllers\Admin\ContactSubmissionsController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Front Page - Redirects authenticated users to portal
Route::get('/', [HomeController::class, 'index'])->name('home');

// Static Pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');

// Contact Page
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

/*
|--------------------------------------------------------------------------
| Authentication Routes (Guest Only)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    // Sign Up (Get Started)
    Route::get('/get-started', [AuthController::class, 'showSignUp'])->name('signup');
    Route::post('/get-started', [AuthController::class, 'signUp'])->name('signup.submit');

    // Sign In
    Route::get('/signin', [AuthController::class, 'showSignIn'])->name('signin');
    Route::post('/signin', [AuthController::class, 'signIn'])->name('signin.submit');

    // Password Reset
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Student Portal Routes (Authenticated Users)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('portal')->name('portal.')->group(function () {
    // Dashboard (default landing after login)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Guidelines (grade-specific content)
    Route::get('/guidelines', [GuidelinesController::class, 'index'])->name('guidelines');
    Route::get('/guidelines/{grade}', [GuidelinesController::class, 'show'])->name('guidelines.show');

    // AI Advisor (Chatfuel integration)
    Route::get('/advisor', [AdvisorController::class, 'index'])->name('advisor');

    // User Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Sign Out
    Route::post('/signout', [AuthController::class, 'signOut'])->name('signout');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Authenticated Admin Users)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Users List & Detail
    Route::get('/user/{user}', [AdminDashboardController::class, 'showUser'])->name('user.show');
    Route::get('/users/{filter?}', [AdminDashboardController::class, 'users'])->name('users');

    // Global Branding (Logo)
    Route::get('/branding', [BrandingController::class, 'index'])->name('branding');
    Route::post('/branding', [BrandingController::class, 'update'])->name('branding.update');

    // CMS - Front Page Content
    Route::get('/content/homepage', [ContentController::class, 'homepage'])->name('content.homepage');
    Route::put('/content/homepage', [ContentController::class, 'updateHomepage'])->name('content.homepage.update');

    // CMS - Pages (About, Privacy, Terms)
    Route::get('/content/pages', [ContentController::class, 'pages'])->name('content.pages');
    Route::get('/content/pages/{page}/edit', [ContentController::class, 'editPage'])->name('content.pages.edit');
    Route::put('/content/pages/{page}', [ContentController::class, 'updatePage'])->name('content.pages.update');

    // CMS - Footer Links
    Route::get('/content/footer', [ContentController::class, 'footer'])->name('content.footer');
    Route::put('/content/footer', [ContentController::class, 'updateFooter'])->name('content.footer.update');

    // Grade-Specific Guidelines
    Route::get('/guidelines', [GuidelinesManagementController::class, 'index'])->name('guidelines');
    Route::get('/guidelines/{grade}/edit', [GuidelinesManagementController::class, 'edit'])->name('guidelines.edit');
    Route::put('/guidelines/{grade}', [GuidelinesManagementController::class, 'update'])->name('guidelines.update');

    // Contact Submissions
    Route::get('/contacts', [ContactSubmissionsController::class, 'index'])->name('contacts');
    Route::get('/contacts/{submission}', [ContactSubmissionsController::class, 'show'])->name('contacts.show');
    Route::delete('/contacts/{submission}', [ContactSubmissionsController::class, 'destroy'])->name('contacts.destroy');
});
