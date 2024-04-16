<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\OrientationLtrController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\PrescriptionsController;
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

Route::get(
    '/form', function () {
        //php artisan serve --host 192.168.20.31 --port=8000 casa
        //php artisan serve --host 172.28.8.154 --port=8000 UIB

         $agent = new Agent();

/*         if ($agent->isMobile())
        {
            return 'Is Mobile '. $agent->platform() .' '. 
                    'Browser: '. $agent->browser() . ' ' . 
                     request()->ip();
        }

        if ($agent->isDesktop())
        {
            $mystring = request()->ip();
            $findme   = '.24';
            $pos = strpos($mystring, $findme);

            // Note our use of ===.  Simply == would not work as expected
            // because the position of 'a' was the 0th (first) character.
            if ($pos === false) {
                return "IP: {$mystring} | Ubicación: Externa";
                echo "'$findme' was not found in the string '$mystring'";
            } else {
                return "IP: {$mystring} | Ubicación: UIB Sede Barranquilla | Is Desktop {$agent->platform()} | Browser: {$agent->browser()}";
                echo "Ubicación: UIB Barranquilla The string. The string '$findme' was found in the string '$mystring'";
                echo " and exists at position $pos";
            }

        } */

        return view('chat.form');
    }
)->name('form');

Route::post('/form/programs/find', [ChatController::class, 'findByQueryProgram'])
->name(
    'form.programs.findByQueryProgram'
);

Route::post('/save-patients-form', [ChatController::class, 'savePatientForm'])
->name(
    'form.savePatientForm'
);

Route::get('/questions/{patient}', [ChatController::class, 'questionPatient'])->name('questions');

Route::post('/save-questions/{patient}', [ChatController::class, 'storeQuestionPatient'])->name('questions.store');

Route::get('/chat/{patient}', [ChatController::class, 'chatPatient'])->name('chat');

Route::post('/chat-services', [ChatController::class, 'serviceChatPatient']);

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
            'programs.findByQueryProgram'
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

    Route::middleware(['auth', 'user-role:SECRETARY|ADMIN'])->group(
    function () {
        Route::post('/users/find', [UsersController::class, 'findByQuery'])
            ->name(
                'users.findByQuery'
            );
    }
);
