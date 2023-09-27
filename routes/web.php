<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\ContactCompanyController;
use App\Http\Controllers\Admin\ContactContactController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use RobersonFaria\DatabaseSchedule\Http\Controllers\ScheduleController;

Route::redirect('/', '/login');
Route::impersonate();

Auth::routes(['register' => false]);
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('logs');
Route::get('schedule', [ScheduleController::class, 'index'])->name('database-schedule');
Route::get('console', [\App\Http\Controllers\ConsoleController::class, 'index'])->name('console');

// ADMINISTRATION
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::resource('permissions', PermissionController::class, ['except' => ['store', 'update', 'destroy']]);
    Route::resource('roles', RoleController::class, ['except' => ['store', 'update', 'destroy']]);
    Route::resource('users', UserController::class, ['except' => ['store', 'update', 'destroy']]);
    Route::resource('contact-companies', ContactCompanyController::class, ['except' => ['store', 'update', 'destroy']]);
    Route::resource('contact-contacts', ContactContactController::class, ['except' => ['store', 'update', 'destroy']]);
    Route::resource('transactions', TransactionController::class, ['except' => ['store', 'update', 'destroy']]);
});

Route::get('send-mail', function () {

    $details = [
        'title' => 'Mail from unpoquitodemayorga.es',
        'body' => 'This is for testing email using smtp'
    ];

    \Mail::to('rodmayes@gmail.com')->send(new \App\Mail\MyTestMail($details));

    dd("Email is Sent.");
});
