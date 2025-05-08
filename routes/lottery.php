<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Lottery\LotteryController;

    Route::get('combinations',[LotteryController::class, 'index'])->name('combinations');
    Route::get('results', [LotteryController::class, 'results'])->name('results');
