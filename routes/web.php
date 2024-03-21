<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrientationLtrController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\PrescriptionsController;
use App\Http\Controllers\ScansController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get(
    '/', function () {
        return view('auth.login');
    }
);

Route::middleware(['auth', 'user-role:ADMIN'])->group(
    function () {
        Route::resource('users', UsersController::class);
    }
);

Route::match (['get', 'post'], '/login',  [AuthController::class, 'login'])
    ->name(
        'login'
    )
    ->middleware(
        'guest'
    );

Route::get('/logout', [AuthController::class, 'logout'])
    ->name(
        'logout'
    )
    ->middleware(
        'auth'
    );

