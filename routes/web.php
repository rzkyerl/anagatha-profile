<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\GitHubAuthController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JobApplyController;
use App\Http\Controllers\Admin\JobListingController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Recruiter\RecruiterCompanyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ============================================================================
// SHARED ROUTES (Available on both domains)
// ============================================================================

// Health check endpoint for Railway
Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()], 200);
});

// Optimized CSS serving with cache headers for auth pages
// This route should be placed before other routes to ensure it's matched first
Route::get('/styles/{type}/{file}', function ($type, $file) {
    // Only handle CSS files
    if (!str_ends_with($file, '.css')) {
        abort(404);
    }
    
    $filePath = public_path("styles/{$type}/{$file}");
    if (file_exists($filePath) && is_file($filePath)) {
        $response = response()->file($filePath);
        
        // Set proper content type
        $response->header('Content-Type', 'text/css; charset=utf-8');
        
        // Set aggressive cache headers for CSS (1 year with revalidation)
        $response->header('Cache-Control', 'public, max-age=31536000, must-revalidate');
        $response->header('Expires', gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
        $response->header('ETag', md5_file($filePath));
        $response->header('Vary', 'Accept-Encoding');
        
        return $response;
    }
    abort(404);
})->where(['type' => '[a-z]+', 'file' => '[a-zA-Z0-9._-]+\.css'])->name('styles.asset');

// Ensure dashboard assets are accessible (fallback for static files)
// This helps ensure CSS and JS files from dashboard directory can be served
Route::get('/dashboard/{path}', function ($path) {
    $filePath = public_path('dashboard/' . $path);
    if (file_exists($filePath) && is_file($filePath)) {
        $response = response()->file($filePath);
        
        // Set proper content type for CSS and JS files
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        if ($extension === 'css') {
            $response->header('Content-Type', 'text/css');
        } elseif ($extension === 'js') {
            $response->header('Content-Type', 'application/javascript');
        }
        
        // Set cache headers
        $response->header('Cache-Control', 'public, max-age=31536000');
        
        return $response;
    }
    abort(404);
})->where('path', '.*')->name('dashboard.asset');

Route::get('/lang/{locale}', function ($locale) {
    $availableLocales = config('app.supported_locales', []);

    if (! in_array($locale, $availableLocales, true)) {
        abort(400);
    }

    session(['locale' => $locale]);

    return redirect()->back();
})->name('lang.switch');

// ============================================================================
// MAIN DOMAIN ROUTES (anagataexecutive.co.id) - Public/Frontend
// ============================================================================

Route::domain(env('APP_DOMAIN', 'anagataexecutive.co.id'))
    ->middleware(['domain.public'])
    ->group(function () {
        
        // Public Pages
        Route::controller(PageController::class)->group(function () {
            Route::get('/', 'landing')->name('landing');
            Route::get('/home', 'home')->middleware(['auth', 'verified', 'role.user'])->name('home');
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
        Route::middleware(['auth', 'verified'])->group(function () {
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
            Route::post('/forgot-password', 'sendPasswordResetLink')->name('password.email');
            Route::get('/reset-password/{token}', 'showResetPasswordForm')->name('password.reset');
            Route::post('/reset-password', 'resetPassword')->name('password.update');
        });

        // Email Verification Routes
        // Notice page requires auth (user must be logged in to see it)
        Route::middleware('auth')->group(function () {
            Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
            Route::post('/email/verification-notification', [VerificationController::class, 'resend'])
                ->middleware('throttle:6,1')
                ->name('verification.send');
        });

        // Verification link can be accessed without auth (user clicks from email)
        Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
            ->middleware('signed')
            ->name('verification.verify');

        // Google OAuth Routes (Public - for user role only)
        Route::controller(GoogleAuthController::class)->group(function () {
            Route::get('/auth/google', 'redirect')->name('google.redirect');
            Route::get('/auth/google/callback', 'callback')->name('google.callback');
        });

        // GitHub OAuth Routes (Public - for user role only)
        Route::controller(GitHubAuthController::class)->group(function () {
            Route::get('/auth/github', 'redirect')->name('github.redirect');
            Route::get('/auth/github/callback', 'callback')->name('github.callback');
        });
    });

// ============================================================================
// ADMIN DOMAIN ROUTES (anagataexecutive.com) - Admin Panel
// ============================================================================

Route::domain(env('ADMIN_DOMAIN', 'anagataexecutive.com'))
    ->middleware(['domain.admin'])
    ->group(function () {
        
        // Admin Auth Routes (Public)
        Route::get('/', function () {
            if (Auth::check()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('admin.login');
        })->name('admin.root');
        
        Route::get('/login', [LoginController::class, 'showLoginForm'])->middleware('guest')->name('admin.login');
        Route::post('/login', [LoginController::class, 'login'])->middleware(['guest', 'throttle:5,1']);

        // Admin Routes (Protected - Admin and Recruiter)
        Route::middleware(['auth', 'verified', 'role.recruiter.admin'])->group(function () {
            // Logout
            Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');
            
            // Dashboard
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
            
            // Job Listings Resource Routes - Admin can see all, recruiter sees only their own (handled in controller)
            Route::get('/job-listings/export', [JobListingController::class, 'export'])->name('admin.job-listings.export');
            Route::get('/job-listings/trashed/list', [JobListingController::class, 'trashed'])->name('admin.job-listings.trashed');
            Route::post('/job-listings/{id}/restore', [JobListingController::class, 'restore'])->name('admin.job-listings.restore');
            Route::delete('/job-listings/{id}/force-delete', [JobListingController::class, 'forceDelete'])->name('admin.job-listings.force-delete');
            Route::post('/job-listings/{id}/update-status', [JobListingController::class, 'updateStatus'])->name('admin.job-listings.update-status');
            Route::resource('job-listings', JobListingController::class)->names([
                'index' => 'admin.job-listings.index',
                'create' => 'admin.job-listings.create',
                'store' => 'admin.job-listings.store',
                'show' => 'admin.job-listings.show',
                'edit' => 'admin.job-listings.edit',
                'update' => 'admin.job-listings.update',
                'destroy' => 'admin.job-listings.destroy',
            ]);
            
            // Job Apply Resource Routes - Admin can see all, recruiter sees only their own (handled in controller)
            Route::get('/job-apply/export', [JobApplyController::class, 'export'])->name('admin.job-apply.export');
            Route::get('/job-apply/trashed/list', [JobApplyController::class, 'trashed'])->name('admin.job-apply.trashed');
            Route::post('/job-apply/{id}/restore', [JobApplyController::class, 'restore'])->name('admin.job-apply.restore');
            Route::delete('/job-apply/{id}/force-delete', [JobApplyController::class, 'forceDelete'])->name('admin.job-apply.force-delete');
            Route::resource('job-apply', JobApplyController::class)->names([
                'index' => 'admin.job-apply.index',
                'create' => 'admin.job-apply.create',
                'store' => 'admin.job-apply.store',
                'show' => 'admin.job-apply.show',
                'edit' => 'admin.job-apply.edit',
                'update' => 'admin.job-apply.update',
                'destroy' => 'admin.job-apply.destroy',
            ]);
            
            // Job Apply File Downloads
            Route::get('/job-apply/{id}/download/cv', [JobApplyController::class, 'downloadCv'])->name('admin.job-apply.download.cv');
            Route::get('/job-apply/{id}/download/portfolio', [JobApplyController::class, 'downloadPortfolio'])->name('admin.job-apply.download.portfolio');
        });
            
        // Admin Only Routes (Protected - Admin exclusive access)
        Route::middleware(['auth', 'verified', 'role.admin'])->group(function () {
            // Users Resource Routes - Admin only (recruiters cannot manage users)
            Route::get('/users/export', [UserController::class, 'export'])->name('admin.users.export');
            Route::get('/users/trashed/list', [UserController::class, 'trashed'])->name('admin.users.trashed');
            Route::post('/users/{id}/restore', [UserController::class, 'restore'])->name('admin.users.restore');
            Route::delete('/users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('admin.users.force-delete');
            Route::resource('users', UserController::class)->names([
                'index' => 'admin.users.index',
                'create' => 'admin.users.create',
                'store' => 'admin.users.store',
                'show' => 'admin.users.show',
                'edit' => 'admin.users.edit',
                'update' => 'admin.users.update',
                'destroy' => 'admin.users.destroy',
            ]);
            
            // Companies - Admin only (index must be before show to avoid route conflict)
            Route::get('/companies', [CompanyController::class, 'index'])->name('admin.companies.index');
            Route::get('/companies/export', [CompanyController::class, 'export'])->name('admin.companies.export');
            Route::get('/companies/{companyName}', [CompanyController::class, 'show'])->name('admin.companies.show');
            
            // Reports - Admin only
            Route::prefix('reports')->name('admin.reports.')->group(function () {
                Route::get('/', [ReportController::class, 'index'])->name('index');
                Route::get('/users', [ReportController::class, 'users'])->name('users');
                Route::get('/jobs', [ReportController::class, 'jobs'])->name('jobs');
                Route::get('/export/overview', [ReportController::class, 'exportOverview'])->name('export.overview');
                Route::get('/export/users', [ReportController::class, 'exportUsers'])->name('export.users');
                Route::get('/export/jobs', [ReportController::class, 'exportJobs'])->name('export.jobs');
            });
        });

        // Profile Settings Routes (Protected - accessible by admin and recruiter)
        Route::middleware(['auth', 'role.recruiter.admin'])->group(function () {
            Route::get('/profile/settings', [AdminProfileController::class, 'show'])->name('admin.profile.settings');
            Route::put('/profile/settings', [AdminProfileController::class, 'update'])->name('admin.profile.update');
            Route::get('/profile/avatar/{filename}', [AdminProfileController::class, 'avatar'])->name('admin.profile.avatar');
        });

        // Recruiter Routes (Protected - recruiter-facing URLs, accessible by recruiter and admin)
        Route::middleware(['auth', 'verified', 'role.recruiter.admin'])->group(function () {
            // Logout
            Route::post('/recruiter/logout', [LoginController::class, 'logout'])->name('admin.recruiter.logout');

            // Dashboard
            Route::get('/recruiter/dashboard', [DashboardController::class, 'index'])->name('admin.recruiter.dashboard');
            Route::get('/recruiter/profile/settings', [AdminProfileController::class, 'show'])->name('admin.recruiter.profile.settings');
            Route::put('/recruiter/profile/settings', [AdminProfileController::class, 'update'])->name('admin.recruiter.profile.update');
            Route::get('/recruiter/profile/avatar/{filename}', [AdminProfileController::class, 'avatar'])->name('admin.recruiter.profile.avatar');

            // Job Listings (recruiter-scoped in controller)
            Route::get('/recruiter/job-listings/export', [JobListingController::class, 'export'])->name('admin.recruiter.job-listings.export');
            Route::get('/recruiter/job-listings/trashed/list', [JobListingController::class, 'trashed'])->name('admin.recruiter.job-listings.trashed');
            Route::post('/recruiter/job-listings/{id}/restore', [JobListingController::class, 'restore'])->name('admin.recruiter.job-listings.restore');
            Route::delete('/recruiter/job-listings/{id}/force-delete', [JobListingController::class, 'forceDelete'])->name('admin.recruiter.job-listings.force-delete');
            Route::post('/recruiter/job-listings/{id}/update-status', [JobListingController::class, 'updateStatus'])->name('admin.recruiter.job-listings.update-status');
            Route::resource('recruiter/job-listings', JobListingController::class)->names([
                'index' => 'admin.recruiter.job-listings.index',
                'create' => 'admin.recruiter.job-listings.create',
                'store' => 'admin.recruiter.job-listings.store',
                'show' => 'admin.recruiter.job-listings.show',
                'edit' => 'admin.recruiter.job-listings.edit',
                'update' => 'admin.recruiter.job-listings.update',
                'destroy' => 'admin.recruiter.job-listings.destroy',
            ]);

            // Job Applications (only for this recruiter in controller)
            Route::get('/recruiter/job-apply/export', [JobApplyController::class, 'export'])->name('admin.recruiter.job-apply.export');
            Route::get('/recruiter/job-apply/trashed/list', [JobApplyController::class, 'trashed'])->name('admin.recruiter.job-apply.trashed');
            Route::post('/recruiter/job-apply/{id}/restore', [JobApplyController::class, 'restore'])->name('admin.recruiter.job-apply.restore');
            Route::delete('/recruiter/job-apply/{id}/force-delete', [JobApplyController::class, 'forceDelete'])->name('admin.recruiter.job-apply.force-delete');
            Route::resource('recruiter/job-apply', JobApplyController::class)->names([
                'index' => 'admin.recruiter.job-apply.index',
                'create' => 'admin.recruiter.job-apply.create',
                'store' => 'admin.recruiter.job-apply.store',
                'show' => 'admin.recruiter.job-apply.show',
                'edit' => 'admin.recruiter.job-apply.edit',
                'update' => 'admin.recruiter.job-apply.update',
                'destroy' => 'admin.recruiter.job-apply.destroy',
            ]);

            // Job Apply File Downloads
            Route::get('/recruiter/job-apply/{id}/download/cv', [JobApplyController::class, 'downloadCv'])->name('admin.recruiter.job-apply.download.cv');
            Route::get('/recruiter/job-apply/{id}/download/portfolio', [JobApplyController::class, 'downloadPortfolio'])->name('admin.recruiter.job-apply.download.portfolio');

            // My Company Routes (Edit only, since data exists from registration)
            Route::get('/recruiter/company', [RecruiterCompanyController::class, 'show'])->name('admin.recruiter.company.show');
            Route::get('/recruiter/company/edit', [RecruiterCompanyController::class, 'edit'])->name('admin.recruiter.company.edit');
            Route::put('/recruiter/company', [RecruiterCompanyController::class, 'update'])->name('admin.recruiter.company.update');
            Route::get('/recruiter/company/logo/{filename}', [RecruiterCompanyController::class, 'companyLogo'])->name('admin.recruiter.company.logo');
        });
        
        // Route aliases for backward compatibility (recruiter.* routes)
        // These will redirect to admin.recruiter.* routes
        Route::middleware(['auth', 'verified', 'role.recruiter.admin'])->group(function () {
            Route::get('/recruiter/dashboard', function () {
                return redirect()->route('admin.recruiter.dashboard');
            })->name('recruiter.dashboard');
            
            Route::get('/recruiter/profile/settings', function () {
                return redirect()->route('admin.recruiter.profile.settings');
            })->name('recruiter.profile.settings');
            
            Route::get('/recruiter/company', function () {
                return redirect()->route('admin.recruiter.company.show');
            })->name('recruiter.company.show');
            
            Route::get('/recruiter/company/edit', function () {
                return redirect()->route('admin.recruiter.company.edit');
            })->name('recruiter.company.edit');
            
            Route::get('/recruiter/company/logo/{filename}', function ($filename) {
                return redirect()->route('admin.recruiter.company.logo', $filename);
            })->name('recruiter.company.logo');
            
            Route::put('/recruiter/company', [RecruiterCompanyController::class, 'update'])->name('recruiter.company.update');
            
            Route::get('/recruiter/job-listings', function () {
                return redirect()->route('admin.recruiter.job-listings.index');
            })->name('recruiter.job-listings.index');
            
            Route::get('/recruiter/job-apply', function () {
                return redirect()->route('admin.recruiter.job-apply.index');
            })->name('recruiter.job-apply.index');
        });
    });

// ============================================================================
// OLD PATH-BASED ROUTING (COMMENTED - Keep for reference/rollback)
// ============================================================================

/*
// Public Pages
Route::controller(PageController::class)->group(function () {
    Route::get('/', 'landing')->name('landing');
    Route::get('/home', 'home')->middleware(['auth', 'verified', 'role.user'])->name('home');
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
Route::middleware(['auth', 'verified'])->group(function () {
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
    Route::post('/forgot-password', 'sendPasswordResetLink')->name('password.email');
    Route::get('/reset-password/{token}', 'showResetPasswordForm')->name('password.reset');
    Route::post('/reset-password', 'resetPassword')->name('password.update');
});

// Email Verification Routes
// Notice page requires auth (user must be logged in to see it)
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::post('/email/verification-notification', [VerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

// Verification link can be accessed without auth (user clicks from email)
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware('signed')
    ->name('verification.verify');

// Google OAuth Routes (Public - for user role only)
Route::controller(GoogleAuthController::class)->group(function () {
    Route::get('/auth/google', 'redirect')->name('google.redirect');
    Route::get('/auth/google/callback', 'callback')->name('google.callback');
});

// GitHub OAuth Routes (Public - for user role only)
Route::controller(GitHubAuthController::class)->group(function () {
    Route::get('/auth/github', 'redirect')->name('github.redirect');
    Route::get('/auth/github/callback', 'callback')->name('github.callback');
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

// Admin Routes (Protected - Admin only section)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'role.recruiter.admin'])->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Dashboard (accessible by both admin and recruiter, but URL is /admin/dashboard)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Job Listings Resource Routes - Admin can see all, recruiter sees only their own (handled in controller)
    Route::get('/job-listings/export', [JobListingController::class, 'export'])->name('job-listings.export');
    Route::get('/job-listings/trashed/list', [JobListingController::class, 'trashed'])->name('job-listings.trashed');
    Route::post('/job-listings/{id}/restore', [JobListingController::class, 'restore'])->name('job-listings.restore');
    Route::delete('/job-listings/{id}/force-delete', [JobListingController::class, 'forceDelete'])->name('job-listings.force-delete');
    Route::post('/job-listings/{id}/update-status', [JobListingController::class, 'updateStatus'])->name('job-listings.update-status');
    Route::resource('job-listings', JobListingController::class);
    
    // Job Apply Resource Routes - Admin can see all, recruiter sees only their own (handled in controller)
    Route::get('/job-apply/export', [JobApplyController::class, 'export'])->name('job-apply.export');
    Route::get('/job-apply/trashed/list', [JobApplyController::class, 'trashed'])->name('job-apply.trashed');
    Route::post('/job-apply/{id}/restore', [JobApplyController::class, 'restore'])->name('job-apply.restore');
    Route::delete('/job-apply/{id}/force-delete', [JobApplyController::class, 'forceDelete'])->name('job-apply.force-delete');
    Route::resource('job-apply', JobApplyController::class);
    
    // Job Apply File Downloads
    Route::get('/job-apply/{id}/download/cv', [JobApplyController::class, 'downloadCv'])->name('job-apply.download.cv');
    Route::get('/job-apply/{id}/download/portfolio', [JobApplyController::class, 'downloadPortfolio'])->name('job-apply.download.portfolio');
});
    
// Admin Only Routes (Protected - Admin exclusive access)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'role.admin'])->group(function () {
    // Users Resource Routes - Admin only (recruiters cannot manage users)
    Route::get('/users/export', [UserController::class, 'export'])->name('users.export');
    Route::get('/users/trashed/list', [UserController::class, 'trashed'])->name('users.trashed');
    Route::post('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('/users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.force-delete');
    Route::resource('users', UserController::class);
    
    // Companies - Admin only (index must be before show to avoid route conflict)
    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::get('/companies/export', [CompanyController::class, 'export'])->name('companies.export');
    Route::get('/companies/{companyName}', [CompanyController::class, 'show'])->name('companies.show');
    
    // Reports - Admin only
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/users', [ReportController::class, 'users'])->name('users');
        Route::get('/jobs', [ReportController::class, 'jobs'])->name('jobs');
        Route::get('/export/overview', [ReportController::class, 'exportOverview'])->name('export.overview');
        Route::get('/export/users', [ReportController::class, 'exportUsers'])->name('export.users');
        Route::get('/export/jobs', [ReportController::class, 'exportJobs'])->name('export.jobs');
    });
});

// Profile Settings Routes (Protected - accessible by admin and recruiter)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role.recruiter.admin'])->group(function () {
    Route::get('/profile/settings', [AdminProfileController::class, 'show'])->name('profile.settings');
    Route::put('/profile/settings', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/avatar/{filename}', [AdminProfileController::class, 'avatar'])->name('profile.avatar');
});

// Recruiter Routes (Protected - recruiter-facing URLs, accessible by recruiter and admin)
Route::prefix('recruiter')->name('recruiter.')->middleware(['auth', 'verified', 'role.recruiter.admin'])->group(function () {
    // Logout (same controller, different route name/prefix)
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard (uses same controller, but URL is /recruiter/dashboard)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile/settings', [AdminProfileController::class, 'show'])->name('profile.settings');
    Route::put('/profile/settings', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/avatar/{filename}', [AdminProfileController::class, 'avatar'])->name('profile.avatar');

    // Job Listings (recruiter-scoped in controller)
    Route::get('/job-listings/export', [JobListingController::class, 'export'])->name('job-listings.export');
    Route::get('/job-listings/trashed/list', [JobListingController::class, 'trashed'])->name('job-listings.trashed');
    Route::post('/job-listings/{id}/restore', [JobListingController::class, 'restore'])->name('job-listings.restore');
    Route::delete('/job-listings/{id}/force-delete', [JobListingController::class, 'forceDelete'])->name('job-listings.force-delete');
    Route::post('/job-listings/{id}/update-status', [JobListingController::class, 'updateStatus'])->name('job-listings.update-status');
    Route::resource('job-listings', JobListingController::class);

    // Job Applications (only for this recruiter in controller)
    Route::get('/job-apply/export', [JobApplyController::class, 'export'])->name('job-apply.export');
    Route::get('/job-apply/trashed/list', [JobApplyController::class, 'trashed'])->name('job-apply.trashed');
    Route::post('/job-apply/{id}/restore', [JobApplyController::class, 'restore'])->name('job-apply.restore');
    Route::delete('/job-apply/{id}/force-delete', [JobApplyController::class, 'forceDelete'])->name('job-apply.force-delete');
    Route::resource('job-apply', JobApplyController::class);

    // Job Apply File Downloads
    Route::get('/job-apply/{id}/download/cv', [JobApplyController::class, 'downloadCv'])->name('job-apply.download.cv');
    Route::get('/job-apply/{id}/download/portfolio', [JobApplyController::class, 'downloadPortfolio'])->name('job-apply.download.portfolio');

    // My Company Routes (Edit only, since data exists from registration)
    Route::get('/company', [RecruiterCompanyController::class, 'show'])->name('company.show');
    Route::get('/company/edit', [RecruiterCompanyController::class, 'edit'])->name('company.edit');
    Route::put('/company', [RecruiterCompanyController::class, 'update'])->name('company.update');
    Route::get('/company/logo/{filename}', [RecruiterCompanyController::class, 'companyLogo'])->name('company.logo');
});
*/
