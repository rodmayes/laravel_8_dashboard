<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Playtomic\ClubController;
use App\Http\Controllers\Playtomic\ResourceController;
use App\Http\Controllers\Playtomic\BookingController;
use App\Http\Controllers\Playtomic\PlaytomicController;

    Route::get('login',[PlaytomicController::class, 'login'])->name('login');
    Route::resource('clubs', ClubController::class, ['except' => ['store', 'update', 'destroy']]);
    Route::get('clubs/availability/{club}',[ClubController::class, 'availability'])->name('clubs.availability');
    Route::resource('resources', ResourceController::class, ['except' => ['store', 'update', 'destroy']]);
    //Route::resource('bookings', BookingController::class, ['except' => ['store', 'update', 'destroy']]);
    Route::group(['prefix' => 'bookings', 'as' => 'bookings.'], function () {
        Route::get('', [BookingController::class, 'index'])->name('index');
        Route::get('create', [BookingController::class, 'create'])->name('create');
        Route::get('{booking}/edit', [BookingController::class, 'edit'])->name('edit');
        Route::get('view-calendar', [BookingController::class, 'viewCalendar'])->name('view-calendar');
        Route::get('make-booking', [BookingController::class, 'makeBooking'])->name('make-booking');
        Route::get('booking', [BookingController::class, 'prebooking'])->name('booking');
        Route::get('generate-links/{booking}', [BookingController::class, 'generatelinks'])->name('generate-links');
        Route::get('{booking}', [BookingController::class, 'show'])->name('show');
        Route::get('pre-booking', [BookingController::class, 'prebooking'])->name('pre-booking');
    });
