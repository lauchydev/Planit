<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/', [EventController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    /* CRUD for Events */
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'handleCreate'])->name('events.handleCreate');
    Route::get('/events/{event}/edit', [EventController::class, 'update'])->name('events.update');
    Route::put('/events/{event}', [EventController::class, 'handleUpdate'])->name('events.handleUpdate');
    Route::delete('/events/{event}', [EventController::class, 'delete'])->name('events.delete');
    
    /* Bookings */
    Route::post('/events/{event}/book', [BookingController::class, 'store'])->name('events.book');
    Route::delete('/events/{event}/bookings/{booking}', [BookingController::class, 'delete'])->name('events.bookings.delete');

    /* User Bookings */
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');



    /* Organiser Dashboard */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /* Protected Profile Routes */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/* Unprotected Event Route (No auth) */
Route::get('/events/{event}', [EventController::class, 'details'])->name('events.details');



require __DIR__.'/auth.php';
