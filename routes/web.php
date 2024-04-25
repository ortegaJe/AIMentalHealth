<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\OrientationLtrController;
use App\Http\Controllers\Patient\Home\PatientHomeController;
use App\Http\Controllers\Patient\Login\PatientController as AuthPatientController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\PrescriptionsController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ScansController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use Jenssegers\Agent\Agent;

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

Route::get('/patient/login', [AuthPatientController::class, 'login'])->name('patient.login');
Route::post('/patient/authenticate', [AuthPatientController::class, 'authenticate'])->name('patient.authenticate');
Route::get('/patient/logout', [AuthPatientController::class, 'logout'])->name('patient.logout');
Route::get('/patient/register',  [RegisterController::class, 'index'])->name('patient.register');
Route::post('/patient/register/store',  [RegisterController::class, 'store'])->name('patient.register.store');
Route::get('/patient/home', [PatientHomeController::class, 'index'])->name('patient.home');
Route::get('/patient/questions/{patient}-{token}', [ChatController::class, 'questionPatient'])->name('questions');
Route::post('/patient/save-questions/{patient}', [ChatController::class, 'storeQuestionPatient'])->name('questions.store');
Route::get('/patient/chat/{patient}-{token}', [ChatController::class, 'chatPatient'])->name('chat');
Route::post('/patient/chat-services', [ChatController::class, 'serviceChatPatient']);

//Route::post('/programs/find', [ChatController::class, 'findByQueryProgram'])->name('findByQueryProgram');

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

Route::middleware(['auth', 'user-role:DOCTOR|ADMIN'])->group(
    function () {
        Route::resource('patients', PatientsController::class);

        Route::get('/scans/{id}/download', [ScansController::class, 'download'])
            ->name(
                'scans.download'
            );

        Route::resource('scans', ScansController::class);

        Route::resource('orientationLtr', OrientationLtrController::class);

        Route::get('/prescriptions/{id}/print', [PrescriptionsController::class, 'print'])
            ->name(
                'prescriptions.print'
            );
        Route::resource('prescriptions', PrescriptionsController::class);

    }
);

Route::middleware(['auth', 'user-role:DOCTOR|SECRETARY|ADMIN'])->group(
    function () {
        Route::resource('appointment', AppointmentController::class);
        Route::post('/patients/find', [PatientsController::class, 'findByQuery'])
            ->name(
                'patients.findByQuery'
            );
        Route::post('/programs/find', [PatientsController::class, 'findByQueryProgram'])
        ->name(
            'programs.findByQuery'
        );

    }
);

Route::middleware(['auth', 'user-role:SECRETARY|ADMIN'])->group(
    function () {
        Route::post('/users/find', [UsersController::class, 'findByQuery'])
            ->name(
                'users.findByQuery'
            );
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
