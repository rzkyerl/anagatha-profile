<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JobApplyController;
use App\Http\Controllers\Admin\JobListingController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Health check endpoint for Railway
Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()], 200);
});

Route::get('/lang/{locale}', function ($locale) {
    $availableLocales = config('app.supported_locales', []);

    if (! in_array($locale, $availableLocales, true)) {
        abort(400);
    }

    session(['locale' => $locale]);

    return redirect()->back();
})->name('lang.switch');

// Public Pages
Route::controller(PageController::class)->group(function () {
    Route::get('/', 'landing')->name('landing');
    Route::get('/home', 'home')->middleware(['auth', 'role.user'])->name('home');
    Route::get('/about', 'about')->name('about');
    Route::get('/services', 'services')->name('services');
    Route::get('/why-us', 'whyUs')->name('why-us');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/jobs', 'jobListing')->name('jobs');
    Route::get('/jobs/{id}', 'jobDetail')->name('job.detail');
});

// Company Logo (Public - for frontend job listings)
Route::get('/company/{filename}', [JobListingController::class, 'companyLogo'])->name('company.logo');

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    // Job Application Routes
    Route::get('/jobs/{id}/apply', [PageController::class, 'jobApplication'])->name('job.apply');
    Route::post('/job-application', [JobApplicationController::class, 'store'])
        ->middleware('throttle:3,1')
        ->name('job.application.store');
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/avatar/{filename}', [ProfileController::class, 'avatar'])->name('profile.avatar');
    
    // History Route
    Route::get('/history', [PageController::class, 'history'])->name('history');
    
    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Contact Form (Public)
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

// Authentication Routes (Public)
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::get('/register/role', 'showRegisterRoleForm')->name('register.role');
    Route::get('/register', 'showRegisterForm')->name('register');
    Route::post('/register', 'register');
    Route::get('/forgot-password', 'showForgotPasswordForm')->name('password.request');
});

// Admin Auth Routes (Public)
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin root redirect to dashboard or login
    Route::get('/', function () {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login');
    });
    
    // Login Routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->middleware('guest')->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
});

// Admin Routes (Protected)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role.recruiter.admin'])->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Users Resource Routes - Export must be before resource to avoid route conflict
    Route::get('/users/export', [UserController::class, 'export'])->name('users.export');
    Route::resource('users', UserController::class);
    
    // Job Listings Resource Routes - Export must be before resource to avoid route conflict
    Route::get('/job-listings/export', [JobListingController::class, 'export'])->name('job-listings.export');
    Route::get('/job-listings/trashed/list', [JobListingController::class, 'trashed'])->name('job-listings.trashed');
    Route::post('/job-listings/{id}/restore', [JobListingController::class, 'restore'])->name('job-listings.restore');
    Route::delete('/job-listings/{id}/force-delete', [JobListingController::class, 'forceDelete'])->name('job-listings.force-delete');
    Route::resource('job-listings', JobListingController::class);
    
    // Job Apply Resource Routes - Export must be before resource to avoid route conflict
    Route::get('/job-apply/export', [JobApplyController::class, 'export'])->name('job-apply.export');
    Route::get('/job-apply/trashed/list', [JobApplyController::class, 'trashed'])->name('job-apply.trashed');
    Route::post('/job-apply/{id}/restore', [JobApplyController::class, 'restore'])->name('job-apply.restore');
    Route::delete('/job-apply/{id}/force-delete', [JobApplyController::class, 'forceDelete'])->name('job-apply.force-delete');
    Route::resource('job-apply', JobApplyController::class);
    
    // Job Apply File Downloads
    Route::get('/job-apply/{id}/download/cv', [JobApplyController::class, 'downloadCv'])->name('job-apply.download.cv');
    Route::get('/job-apply/{id}/download/portfolio', [JobApplyController::class, 'downloadPortfolio'])->name('job-apply.download.portfolio');
    
    // Users Resource Routes - restore and force delete
    Route::get('/users/trashed/list', [UserController::class, 'trashed'])->name('users.trashed');
    Route::post('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('/users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.force-delete');
    
    // Recruiter Routes (Placeholder - replace with actual controller when available)
    Route::get('/recruiter', function () {
        return redirect()->route('admin.dashboard')->with('info', 'Recruiters page coming soon');
    })->name('recruiter.index');
});