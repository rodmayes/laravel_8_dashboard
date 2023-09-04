<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Playtomic\ClubController;
use App\Http\Controllers\Playtomic\ResourceController;
use App\Http\Controllers\Playtomic\BookingController;
use App\Http\Controllers\Playtomic\PlaytomicController;

Route::get('prebooking', [BookingController::class, 'prebooking'])->name('prebooking');

    Route::get('login',[PlaytomicController::class, 'login'])->name('login');
    Route::resource('clubs', ClubController::class, ['except' => ['store', 'update', 'destroy']]);
    Route::get('clubs/availability/{club}',[ClubController::class, 'availability'])->name('clubs.availability');
    Route::resource('resources', ResourceController::class, ['except' => ['store', 'update', 'destroy']]);
    Route::resource('bookings', BookingController::class, ['except' => ['store', 'update', 'destroy']]);
    Route::group(['prefix' => 'bookings', 'as' => 'bookings.'], function () {
        Route::get('pre-booking', [BookingController::class, 'prebooking'])->name('pre-booking');
        Route::get('make-booking', [BookingController::class, 'makeBooking'])->name('make-prebooking');
        Route::get('generate-links/{booking}', [BookingController::class, 'generatelinks'])->name('generate-links');
//        Route::get('pre-booking', [BookingController::class, 'prebooking'])->name('pre-booking');
    });
