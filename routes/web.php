<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::controller(PageController::class)->group(function () {
    Route::get('/', 'home');
    Route::get('/about', 'about')->name('about');
    Route::get('/services', 'services')->name('services');
    Route::get('/why-us', 'whyUs')->name('why-us');
    Route::get('/contact', 'contact')->name('contact');
});

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
