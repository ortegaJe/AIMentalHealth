<?php

namespace App\Http\Controllers\Patient\Home;

use App\Enums\PromptAI;
use App\Http\Controllers\Controller;
use App\Mail\DemoMail;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Remision;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PatientHomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:patient');
    }
    
    public function index()
    {
        $patient_id =  Auth::user()->id;

        $patient = Patient::findOrFail($patient_id);

        $remisions = $patient->remisions()->where('patient_id', $patient->id)->get();

        // A list of doctor-patient appointments
        $appointments = Appointment::join('users', 'users.id', 'appointments.user_id')
                                        ->join('patients', 'patients.id', 'appointments.patient_id')
                                        ->join('question_patient', 'question_patient.patient_id', 'patients.id')
                                        ->groupBy('appointments.patient_id','patients.id','users.name','appointments.date','appointments.start_time','question_patient.location')
                                        ->select('appointments.patient_id','patients.id','users.name','appointments.date','appointments.start_time','question_patient.location')
                                            ->where('appointments.patient_id', $patient->id)->get();
                                        
                                        //return $appointments;

        return view('patient-home.index', compact('patient', 'appointments', 'remisions'));
    }

    public function serviceChatPatient(Request $request)
    {
        $patient_id =  Auth::user()->id;

        $patient = Patient::findOrFail($patient_id);

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
                    "content" => PromptAI::CONTEXT_SYSTEM_NO_APPOINTMENT .
                    "- Esta es la información o datos personales del USUARIO {$patient}." .
                    "- Informa al USUARIO la cita agendada que se le asignó: " .
                    (isset($newAppointment) ? "El día " . $newAppointment->date . " a las " . $newAppointment->start_time : "No tiene cita agendada.") .
                    " Si te pregunta, solo le dirás estos datos: 'FECHA' y 'HORA' de la cita solamente. La hora de la cita se le daras en formato de 12 horas.",
                ];
                // Agregar el contexto inicial al arreglo de mensajes
                $messages = [$context];
                error_log(__LINE__ . __METHOD__ . ' encontro cita --->' . var_export($messages, true));

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
