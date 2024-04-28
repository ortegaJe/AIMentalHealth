<?php

namespace App\Http\Controllers;

use App\Enums\PromptAI;
use App\Http\Requests\PatientFormRequest;
use App\Http\Requests\QuestionFormRequest;
use App\Mail\AlertEmail;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Throwable;
use App\Mail\DemoMail;
use App\Models\User;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Jenssegers\Agent\Agent;

class ChatController extends Controller
{
    public function questionPatient(Patient $patient, $token)
    {               
        $questions = DB::table('questions')->get(['id','name']);
        return view('patient-home.question', compact('patient', 'questions'));
    }

    public function storeQuestionPatient(Request $request, Patient $patient)
    { 
        //php artisan serve --host 192.168.20.31 --port=8000 casa
        //php artisan serve --host 172.28.8.154 --port=8000 UIB
        $agent = new Agent();
        // Determina el tipo de dispositivo
        $deviceType = ($agent->isDesktop()) ? 'Desktop' : (($agent->isMobile()) ? 'Mobile' : 'Otro');
        // Determinar la ubicación basada en la dirección IP
        $ipAddress = request()->ip();
        $location = strpos($ipAddress, '.0') !== false ? 'IUB Sede Barranquilla Plaza de la Paz - Área de Bienestar' : 'Externa';

        $questions = DB::table('questions')->pluck('id');

        // Definir las reglas de validación en un array
        $rules = [];
        $messages = [];

        // Iterar sobre los IDs de las preguntas
        foreach ($questions as $questionId) {
            $rules['question_' . $questionId] = 'required';
            $messages['question_' . $questionId . '.required'] = 'Todas las preguntas son obligatorias a responder.';
        }

        // Aplicar las reglas de validación
        $validator = Validator::make($request->all(), $rules, $messages);

        //dd($validator);

        // Verificar si hay errores de validación
        if ($validator->fails()) {
            // Si hay errores, redirigir de vuelta al formulario
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Si no hay errores de validación, proceder a guardar las respuestas
        foreach ($request->all() as $key => $value) {
            // Verificar si la clave del campo es una pregunta
            if (strpos($key, 'question_') === 0) {
                // Extraer el ID de la pregunta
                $questionId = str_replace('question_', '', $key);

                // Guardar la respuesta en la base de datos
                DB::table('question_patient')->insert([
                    'question_id' => $questionId,
                    'patient_id' => $patient->id,
                    'confirmed' => $value,
                    'device' => $deviceType,
                    'ip' => $ipAddress,
                    'location' => $location,
                    'created_at' => now('America/Bogota')
                ]);
            }
        }

        $token = fake()->uuid();
        return redirect()->route('chat', ['patient' => $patient, 'token' => $token]);
    }

    public function chatPatient(Request $request, Patient $patient, $token)
    {
        /* $questionPatient = DB::table('questions as a')
                        ->leftJoin('question_patient as b', 'b.question_id', 'a.id')
                        ->leftJoin('patients as c', 'c.id', 'b.patient_id')
                        ->where('b.patient_id', $patient->id)
                        ->select(
                                 'a.id as questionID',
                                 'c.identification',
                                 'c.full_name as patient_name',
                                 'a.name as question', 
                                 DB::raw("CASE WHEN b.confirmed = 1 THEN 'si' ELSE 'no' END as answer")
                                 )->get();*/

        //$total = 9;
        $riskStatus = DB::table('question_patient as a')
                            ->select(
                                DB::raw("CASE WHEN SUM(a.confirmed) BETWEEN 0 AND 2 THEN 'RIESGO BAJO' 
                                                WHEN SUM(a.confirmed) BETWEEN 3 AND 4 THEN 'RIESGO MODERADO'
                                                    WHEN SUM(a.confirmed) BETWEEN 5 AND 6 THEN 'RIESGO ALTO'
                                                        WHEN SUM(a.confirmed) BETWEEN 7 AND 8 THEN 'PRECISA INGRESO'
                                                            ELSE 'SIN REGISTROS DE PUNTUACIÓN'
                                                                END as riesgo")
                                )->where('a.patient_id', $patient->id)->first();

                               //return $riskStatus;

        if ($riskStatus->riesgo === 'RIESGO ALTO' || $riskStatus->riesgo === 'PRECISA INGRESO') 
        {   
            $presentDate = now('America/Bogota')->toDateString();
            $ifExistsAppointment = $patient->appointments()
                                    ->where('patient_id', $patient->id)
                                    ->where('date', $presentDate)
                                    ->count();

            //return $ifExistsAppointment;

          if ($ifExistsAppointment > 0 )
            {
                error_log(__LINE__ . __METHOD__ . ' Existe Cita -->' . var_export($ifExistsAppointment, true));
            } else {
                $time = now('America/Bogota');
                $date = $time->isoFormat('Y-M-D');

                $hourFaker = fake()->numberBetween(9, 18);
                $start_time = $time->isoFormat($hourFaker.':mm');

                $hourFaker1 = substr($start_time, 0, -3) + 1;
                $end_time = $time->isoFormat($hourFaker1.':mm');

                $newAppointment = DB::table('appointments')->insert([
                        'date' => $date,
                        'start_time' => $start_time,
                        'end_time' => $end_time,
                        'motivation' => $riskStatus->riesgo,
                        'user_id' => 1,
                        'patient_id' => $patient->id,
                        'created_at' => $time,
                        'updated_at' => $time
                ]);

                $location = DB::table('question_patient')->where('patient_id', $patient->id)->first(['location']);
                
                $carbon = Carbon::parse();
                // Enviar email con datos de la cita asignada al paciente
                $mailData = [
                    'title' => $patient->full_name,
                    'risk' => $riskStatus->riesgo,
                    'location' => $location->location,
                    //'body' => 'el día ' . $carbon->toDateString($appointmentTemp->date) . ' y hora ' . $carbon->isoFormat($appointmentTemp->start_time, 'h:mm A')
                ];
                    
                Mail::to('admisiones@mentalhealth.ai.com')->send(new AlertEmail($mailData));
            }
        }

        /*$now = now('America/Bogota');
        $today = $now->today();
        $tomorrow = $now->tomorrow()->isoFormat('Y-M-D');
        //$date = $now->isoFormat('Y-M-D');
        return $today;
        return $tomorrow; */

        /* $presentDate = now('America/Bogota')->toDateString();
        $ifExistsAppointment = $patient->appointments()
                                ->where('patient_id', $patient->id)
                                ->where('date', $presentDate)
                                ->count();

        return $ifExistsAppointment > 0 ? 'TIENE CITA ASIGNADA' : 'NO TIENE CITA ASIGANDA'; */

        //$newAppointment = DB::table('appointments')->where('patient_id', $patient->id)->first(['date','start_time']);
        //return (isset($newAppointment) ? "El día " . $newAppointment->date . " a las " . $newAppointment->start_time : "No tiene cita agendada.");

        return view('patient-home.chat.index', compact('patient', 'token'));
    }

    public function serviceChatPatient(Request $request)
    {
        $patientId = $request->input('patient');

        $patient = Patient::findOrFail($patientId);

        $questionPatient = DB::table('questions as a')
                                ->leftJoin('question_patient as b', 'b.question_id', 'a.id')
                                ->leftJoin('patients as c', 'c.id', 'b.patient_id')
                                ->where('b.patient_id', $patient->id)
                                ->select(
                                        'a.id as questionID',
                                        'c.identification',
                                        'c.full_name as patient_name',
                                        'a.name as question', 
                                        DB::raw("CASE WHEN b.confirmed = 1 THEN 'si' ELSE 'no' END as answer")
                                        )->get();

        //$total = 7;
        $riskStatus = DB::table('question_patient as a')
                            ->select(
                                DB::raw("CASE WHEN SUM(a.confirmed) BETWEEN 0 AND 2 THEN 'RIESGO BAJO' 
                                            WHEN SUM(a.confirmed) BETWEEN 3 AND 4 THEN 'RIESGO MODERADO'
                                                WHEN SUM(a.confirmed) BETWEEN 5 AND 6 THEN 'RIESGO ALTO'
                                                    WHEN SUM(a.confirmed) BETWEEN 7 AND 8 THEN 'PRECISA INGRESO'
                                                        ELSE 'SIN REGISTROS DE PUNTUACIÓN'
                                                            END as riesgo")
                                )->where('a.patient_id', $patient->id)->first();

                                error_log(__LINE__ . __METHOD__ . ' ESTADO DE RIESGO --->' . var_export($riskStatus, true));

                                // Cita asignada del paciente dependiendo del estado de riesgo
                                $MyAppointments = $patient->appointments()->where('patient_id', $patient->id)->get();

                                $presentDate = now('America/Bogota')->toDateString();
                                $ifExistsAppointment = $patient->appointments()
                                    ->where('patient_id', $patient->id)
                                    ->where('date', $presentDate)
                                    ->count();
                                
                                // Validar si ya tiene cita asignada para el día de hoy, solo es posible una cita al día
                                if ($ifExistsAppointment > 0) {   
                                    // Si ya tiene cita asignada solo se le comparten los datos para saber su cita asignada para el día
                                    $MyAppointments;
                                } else {
                                    // Mensaje del usuario
                                    $userMessage = str_replace(' ', '', $request->post('content'));
                                
                                    // Array de palabras clave para pedir o asignar cita
                                    $citaKeywords = ['cita', 'agendar', 'agenda', 'programar', 'fecha', 'hora'];
                                    // Array de palabras clave negativas
                                    $negativasKeywords = ['no', 'cancelar', 'evitar', 'omitir', 'rechazar', 'desear'];
                                
                                    // Función para verificar si el mensaje implica la solicitud o asignación de una cita
                                    function validarCita($message, $citaKeywords, $negativasKeywords) {
                                        // Convertir el mensaje a minúsculas para hacer la comparación insensible a mayúsculas y minúsculas
                                        $message = strtolower($message);
                                
                                        // Verificar si alguna palabra clave de cita está presente en el mensaje del usuario
                                        foreach ($citaKeywords as $keyword) {
                                            if (strpos($message, $keyword) !== false) {
                                                // Verificar si alguna palabra clave negativa está presente en el mensaje
                                                foreach ($negativasKeywords as $negKeyword) {
                                                    if (strpos($message, $negKeyword) !== false) {
                                                        return false; // Si hay una palabra negativa, el usuario no desea una cita
                                                    }
                                                }
                                                return true; // Si hay una palabra clave de cita y ninguna negativa, el usuario desea una cita
                                            }
                                        }
                                
                                        return false; // Si no hay ninguna palabra clave de cita, el usuario no desea una cita
                                    }
                                
                                    // Verificar si el mensaje implica la solicitud o asignación de una cita
                                    if (validarCita($userMessage, $citaKeywords, $negativasKeywords)) {
                                        // Si el mensaje implica una cita, se asigna una nueva cita al paciente
                                        //$string = $request->post('content');
                                
                                        $date = now('America/Bogota');
                                        $day= fake()->numberBetween(0, 5);
                                        $fechaOportunidad = Carbon::parse($date->addDays($day)->isoFormat('Y-M-D'));
                                        //return $oportunidad->isoFormat('Y-M-D');
                                
                                        $hourFaker = fake()->numberBetween(9, 18);
                                        $start_time = $date->isoFormat($hourFaker.':mm');
                                
                                        $hourFaker1 = substr($start_time, 0, -3) + 1;
                                        $end_time = $date->isoFormat($hourFaker1.':mm');
                                
                                        $newAppointment = DB::table('appointments')->insert([
                                            'date' => $fechaOportunidad,
                                            'start_time' => $start_time,
                                            'end_time' => $end_time,
                                            'motivation' => $riskStatus->riesgo,
                                            'user_id' => 1,
                                            'patient_id' => $patient->id,
                                            'created_at' => now('America/Bogota'),
                                            'updated_at' => now('America/Bogota'),
                                        ]);
                                
                                        // Enviar email con datos de la cita asignada al paciente
                                        $appointmentTemp = DB::table('appointments')->where('patient_id', $patient->id)->first(['date','start_time']);
                                        $date = Carbon::parse($appointmentTemp->date)->isoFormat('D-M-Y');
                                        $startTime = Carbon::parse($appointmentTemp->start_time)->format('g:i:s A');
                                        
                                        $mailData = [
                                            'title' => $patient->full_name,
                                            'body' => 'Fecha de la cita: ' . $date . ' - Hora de la cita ' . $startTime ,
                                        ];
                                
                                        Mail::to($patient->email)->send(new DemoMail($mailData));
                                    }
                                }

                                $newAppointment = DB::table('appointments')->where('patient_id', $patient->id)->first(['date','start_time']);

                // Condicionar asistente para darle contexto
                $context = [
                    "role" => "system",
                    "content" => PromptAI::CONTEXT_SYSTEM_APPOINTMENT .
                    "- Esta es la información o datos personales del USUARIO {$patient}." .
                    "- Informa al USUARIO la cita agendada que se le asignó: " .
                    (isset($newAppointment) ? "El día " . $newAppointment->date . " a las " . $newAppointment->start_time : "No tiene cita agendada.") .
                    " Si te pregunta, solo le dirás estos datos: 'FECHA' y 'HORA' de la cita solamente. La hora de la cita se le daras en formato de 12 horas." . 
                    "Cuando el USUARIO finalize la conversación dile que sera redirigido al portal web",
                ];
                // Agregar el contexto inicial al arreglo de mensajes
                $messages = [$context];
                //error_log(__LINE__ . __METHOD__ . ' encontro cita --->' . var_export($messages, true));

                // Obtenemos el contenido del mensaje del usuario
                $content = $request->post('content');
                // Agregar el mensaje del usuario al array de mensajes
                $messages[] = ["role" => "user", "content" => $content];

                $response = Http::withHeaders([
                    "Content-Type" => "application/json",
                    "Authorization" => "Bearer " . env('OPENAI_API_KEY'),
                ])->post('https://api.openai.com/v1/chat/completions', [
                    "model" => "gpt-3.5-turbo",
                    "messages" => $messages,
                    "temperature" => 0.6, //La temperatura es un parámetro que controla la aleatoriedad en las predicciones del modelo. Temperatura baja (0 a 0.3): Resultados más centrados, coherentes y conservadores. Temperatura media (0.3 a 0.7): Creatividad y coherencia equilibradas. Temperatura alta (0.7 a 1): El modelo tiende a generar respuestas más diversas y creativas, arriesgándose a ser menos coherente y preciso en ciertos contextos.
                    //"frequency_penalty" => 1.5,
                    "max_tokens" => 150,
                ])->json();

                error_log(__LINE__ . __METHOD__ . ' respuesta del asistente --->' . var_export($response, true));

                $response_content = $response['choices'][0]['message']['content'];

                $messages[] = ["role" => "assistant", "content" => $response_content];

                // Ruta y nombre del archivo json de los chats
                $folderPath = 'public/chats/'.$patient->token.'/';
                $fileName = 'chat.json';
                $filePath = $folderPath . $fileName;

                // Verificar si la carpeta existe, si no, crearla
                if (!Storage::exists($folderPath)) {
                    Storage::makeDirectory($folderPath, 0777, true); // Crear la carpeta con permisos de lectura y escritura
                }

                //$filePath = public_path();

                // Verificar si el archivo JSON existe en la carpeta
                if (!Storage::exists($filePath)) {
                    // Crear un nuevo archivo JSON vacío si no existe
                    Storage::put($filePath, '[]');
                }

                // Cargar datos existentes del archivo JSON
                $chatData = json_decode(Storage::get($filePath), true);

                // Agregar la nueva interacción a los datos de la conversación
                $chatData[] = [
                    "role" => "user",
                    "content" => $content,
                    "date" => $request->post('date'),
                ];
                $date = now('America/Bogota');
                $chatData[] = [
                    "role" => "assistant",
                    "content" => $response_content,
                    "date" => $date->format('d-m-Y g:i:s A'),
                ];

                // Guardar los datos actualizados en el archivo JSON
                Storage::put($filePath, json_encode($chatData, JSON_PRETTY_PRINT));

               /*  $storeChat = DB::table('chats')->insert([
                    'chat_info'  =>  $response_content,
                    'user_info'  =>  $content,
                    'patient_id' => $patient->id,
                    'created_at' => now('America/Bogota'),
                    'updated_at' => now('America/Bogota')
            ]); */

                return response()->json($response_content);
    }
}
