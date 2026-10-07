<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventPublicController;

use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminRegistrationController;
use App\Http\Controllers\RegistrationController;

Route::get('/event-images/{id}', [\App\Http\Controllers\EventImageController::class, 'show'])->whereUuid('id')->name('event-images.show');

// PUBLIC homepage
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events/load-more', [HomeController::class, 'loadMore'])->name('events.loadMore');

// PUBLIC event details
Route::get('/events/{event}', [EventPublicController::class, 'show'])->name('events.show');

// AUTH dashboard (users + admins)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Auth-only actions
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/show', [ProfileController::class, 'show'])->name('profile.show');

    Route::post('/events/{event}/book', [RegistrationController::class, 'store'])->name('events.book');
    Route::delete('/events/{event}/book', [RegistrationController::class, 'destroy'])->name('events.unbook');
});

// ADMIN routes
Route::prefix('admin')
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.')
    ->group(function () {

        // Events (admin)
        Route::get('/events', [AdminEventController::class, 'index'])->name('events.index');
        Route::get('/events/create', [AdminEventController::class, 'create'])->name('events.create');
        Route::post('/events', [AdminEventController::class, 'store'])->name('events.store');
        Route::get('/events/{event}/edit', [AdminEventController::class, 'edit'])->name('events.edit');
        Route::put('/events/{event}', [AdminEventController::class, 'update'])->name('events.update');
        Route::patch('/events/{event}/archive', [AdminEventController::class, 'archive'])->name('events.archive');

        // Categories (admin)
        Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('categories.create');
        Route::get('/categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

        // Registrations (admin)
        Route::get('/registrations', [AdminRegistrationController::class, 'index'])->name('registrations.index');

    

    });

require __DIR__.'/auth.php';

