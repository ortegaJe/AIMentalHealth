<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrientationLtrController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\ScansController;
use App\Http\Controllers\UsersController;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', function (Request $request) {
    return dd($request->user());
});

Route::group(['prefix' => 'v1', 'namespace' => '\App\Http\Controllers'], function() {
    Route::apiResource('users', UsersController::class);
});

Route::group(['prefix' => 'v1', 'namespace' => '\App\Http\Controllers'], function() {
    Route::apiResource('appointment', AppointmentController::class);

    Route::apiResource('patients', PatientsController::class);

    Route::apiResource('orientationLtr', OrientationLtrController::class);

    Route::apiResource('scans', ScansController::class);

});