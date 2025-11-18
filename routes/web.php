<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// Health check endpoint for Railway
Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()], 200);
});

// Google Sheets test endpoint (for debugging)
Route::get('/test-google-sheets', [ContactController::class, 'testGoogleSheets'])
    ->name('test.google-sheets');

Route::controller(PageController::class)->group(function () {
    Route::get('/', 'home');
    Route::get('/about', 'about')->name('about');
    Route::get('/services', 'services')->name('services');
    Route::get('/why-us', 'whyUs')->name('why-us');
    Route::get('/contact', 'contact')->name('contact');
});

Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');