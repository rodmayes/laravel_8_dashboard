<?php
use Illuminate\Support\Facades\Route;

// DASHBOARD
Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('',[\App\Http\Controllers\LineUps\DashboardController::class, 'index'])->name('index');
    Route::get('calendar',[\App\Http\Controllers\LineUps\DashboardController::class, 'calendar'])->name('calendar.index');
});

// ADMINISTRATION
Route::prefix('administration')->name('administration.')->group(function () {
    Route::resource('year', \App\Http\Controllers\LineUps\YearController::class, ['except' => ['store', 'update', 'destroy']]);
    Route::resource('competition', \App\Http\Controllers\LineUps\CompetitionController::class, ['except' => ['store', 'update', 'destroy']]);
    Route::resource('team', \App\Http\Controllers\LineUps\TeamController::class, ['except' => ['store', 'update', 'destroy']]);
    Route::resource('round', \App\Http\Controllers\LineUps\RoundController::class, ['except' => ['store', 'update', 'destroy']]);
    Route::resource('player', \App\Http\Controllers\LineUps\PlayerController::class, ['except' => ['store', 'update', 'destroy']]);
    Route::resource('configuration', \App\Http\Controllers\LineUps\ConfigurationController::class, ['except' => ['store', 'update', 'destroy']]);

    Route::get('/', [\App\Http\Controllers\LineUps\AdministrationController::class, 'index'])->name('index');
    Route::get('year/get-list', [\App\Http\Controllers\LineUps\YearController::class, 'getList'])->name('year.get-list');
    Route::get('competition/get-list', [\App\Http\Controllers\LineUps\CompetitionController::class, 'getList'])->name('competition.get-list');
    Route::get('team/get-list', [\App\Http\Controllers\LineUps\TeamController::class, 'getList'])->name('team.get-list');
    Route::get('round/get-list', [\App\Http\Controllers\LineUps\RoundController::class, 'getList'])->name('round.get-list');
    Route::get('player/get-list', [\App\Http\Controllers\LineUps\PlayerController::class, 'getList'])->name('player.get-list');
    Route::get('configuration/get-list', [\App\Http\Controllers\LineUps\ConfigurationController::class, 'getList'])->name('configuration.get-list');
});
