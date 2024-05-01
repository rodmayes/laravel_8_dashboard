<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use RobersonFaria\DatabaseSchedule\Http\Controllers\ScheduleController;
use App\Http\Controllers\PolicyAndLegalController;

Route::redirect('/', '/login');
Route::impersonate();

Auth::routes(['register' => false]);

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('logs');
Route::get('schedule', [ScheduleController::class, 'index'])->name('database-schedule');
Route::get('console', [\App\Http\Controllers\ConsoleController::class, 'index'])->name('console');
Route::get('user/edit-noadmin', [UserController::class, 'editNoAdmin'])->name('user.edit-noadmin');

Route::get('privacy-policy', [PolicyAndLegalController::class, 'PrivacyPolicy'])->name('privacy-policy');

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::post('user/set-avatar/{user}', [\App\Http\Livewire\User\Edit::class, 'uploadAvatar'])->name('user.set-avatar');
});

// ADMINISTRATION
Route::prefix('user_management')->name('user_management.')->middleware(['auth'])->group(function () {
    Route::resource('permissions', PermissionController::class, ['except' => ['store', 'update', 'destroy'], 'middleware' => ['role:admin']]);
    Route::resource('roles', RoleController::class, ['except' => ['store', 'update', 'destroy'], 'middleware' => ['role:admin']]);
    Route::resource('users', UserController::class, ['except' => ['store', 'update', 'destroy'], 'middleware' => ['role:admin']]);
});

// CAPTCHA
Route::get('refresh_captcha', function(){
    return response()->json(['captcha'=> captcha_img()]);
})->name('refresh_captcha');

Route::get('send-mail', function () {
    $details = [
        'title' => 'Mail from '.env('APP_URL','rodmayes'),
        'body' => 'This is for testing email using smtp'
    ];

    \Mail::to(env('MAIL_TEST'))->send(new \App\Mail\MyTestMail($details));

    dd("Email is Sent.");
});

Route::get('datetime-iso', function(){
    $booking = App\Models\Booking::all()->last();
    $timetable = App\Models\Timetable::where('name', '18:00')->first();
    $data = Carbon\Carbon::createFromFormat('d-m-Y H:i', $booking->started_at->format('d-m-Y').' '.$timetable->name, 'Europe/Andorra')->format(DateTime::ISO8601);
    dd($data);
});
