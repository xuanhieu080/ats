<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/recruitment-request', function () {
        return Inertia::render('Recruitment/HiringRequest');
    })->name('recruitment-request');
    Route::get('/talent-pool', function () {
        return Inertia::render('TalentPool/Index');
    })->name('talent-pool');
    Route::get('/system-data/roles', function () {
        return Inertia::render('SystemData/Roles/Index');
    })->name('roles.index');
    
});
