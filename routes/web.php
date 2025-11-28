<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
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

Route::controller(PageController::class)->group(function () {
    Route::get('/', 'landing')->name('landing');
    Route::get('/home', 'home')->name('home');
    Route::get('/about', 'about')->name('about');
    Route::get('/services', 'services')->name('services');
    Route::get('/why-us', 'whyUs')->name('why-us');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/jobs', 'jobListing')->name('jobs');
    Route::get('/jobs/{id}', 'jobDetail')->name('job.detail');
    Route::get('/jobs/{id}/apply', 'jobApplication')->name('job.apply');
    Route::get('/form-jobs', 'jobApplication')->name('form.jobs');
    Route::get('/profile-test', 'profile')->name('profile.test'); // For frontend testing
    Route::get('/history-test', 'history')->name('history.test'); // For frontend testing
});

Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

Route::post('/job-application', [JobApplicationController::class, 'store'])
    ->middleware('throttle:3,1')
    ->name('job.application.store');

// Authentication Routes
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::get('/register', 'showRegisterForm')->name('register');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth');
    Route::get('/forgot-password', 'showForgotPasswordForm')->name('password.request');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});