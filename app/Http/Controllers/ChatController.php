<?php

namespace App\Http\Controllers;

use App\Enums\ContextAISystem;
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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ChatController extends Controller
{
    public function findByQueryProgram(Request $request)
    {
        $result = DB::table('programs')->select('id', DB::raw("name as text"))
            ->where(
                'name',
                'LIKE', '%' . request('queryTerm') . '%'
            )
            ->get(
            );
        return response()->json($result);
    }

    public function savePatientForm(PatientFormRequest $request)
    {
        $validated = $request->validated();
        $patient = Patient::create($validated);

        return redirect()->route('questions',['patient' => $patient]);
    }

    public function questionPatient(Patient $patient)
    {   
        $questions = DB::table('questions')->get(['id','name']);

        return view('chat.question', compact('patient', 'questions'));
    }

    public function storeQuestionPatient(QuestionFormRequest $request, Patient $patient)
    {   
        //$validator = Validator::make($request->all(), ['required'], ['Todas las preguntas deben ser respondidas para poder enviar el sondeo.']);
        //return $validator->validate();

        //$validated = $request->validated();
        //return $validated;

        // Iterar sobre los datos del formulario para guardar cada respuesta
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
                    'created_at' => now('America/Bogota')
                ]);
            }
        }

        return redirect()->route('chat', ['patient' => $patient]);
        
    }

    public function chatPatient(Patient $patient, Request $request)
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

        //$total = 5;
        $riskStatus = DB::table('question_patient as a')
                            ->select(
                                DB::raw("CASE WHEN SUM(a.confirmed) <= 2 THEN 'RIESGO BAJO' 
                                                WHEN SUM(a.confirmed) <= 4 THEN 'RIESGO MODERADO'
                                                    WHEN SUM(a.confirmed) <= 6 THEN 'RIESGO ALTO'
                                                        WHEN SUM(a.confirmed) <= 8 THEN 'PRECISA INGRESO'
                                                            ELSE 'SIN PUNTUACIÓN'
                                                                END as riesgo")
                                )->where('a.patient_id', $patient->id)->first();

                               //return $riskStatus;

                                if ($riskStatus->riesgo === 'RIESGO ALTO' || $riskStatus->riesgo === 'PRECISA INGRESO') 
                                {
                                    $appointmentTemp = DB::table('appointments')->where('patient_id', $patient->id)->first(['date','start_time']);
                                        
                                    $carbon = Carbon::parse();
                                    // Enviar email con datos de la cita asignada al paciente
                                    $mailData = [
                                        'title' => $patient->full_name,
                                        //'body' => 'el día ' . $carbon->toDateString($appointmentTemp->date) . ' y hora ' . $carbon->isoFormat($appointmentTemp->start_time, 'h:mm A')
                                    ];
                                     
                                    //Mail::to('admisiones@mentalhealth.ai.com')->send(new AlertEmail($mailData));
                                    
                                    $presentDate = now('America/Bogota')->toDateString();
                                    $ifExistsAppointment = $patient->appointments()
                                                            ->where('patient_id', $patient->id)
                                                            ->where('date', $presentDate)
                                                            ->get();

                                    //return $ifExistsAppointment;

                                /*  if (count($ifExistsAppointment) > 0 )
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
                                    } */
                                }

        return view('chat.index', compact('patient'));
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
                                DB::raw("CASE WHEN SUM(a.confirmed) <= 2 THEN 'RIESGO BAJO' 
                                                WHEN SUM(a.confirmed) <= 4 THEN 'RIESGO MODERADO'
                                                    WHEN SUM(a.confirmed) <= 6 THEN 'RIESGO ALTO'
                                                        WHEN SUM(a.confirmed) <= 8 THEN 'PRECISA INGRESO'
                                                            ELSE 'SIN PUNTUACIÓN'
                                                                END as riesgo")
                                )->where('a.patient_id', $patient->id)->first();

                                error_log(__LINE__ . __METHOD__ . ' ESTADO DE RIESGO --->' . var_export($riskStatus, true));

                                // Cita asignada del paciente dependiendo del estado de riesgo
                                $MyAppointments = $patient->appointments()->where('patient_id', $patient->id)->get();

                                $presentDate = now('America/Bogota')->toDateString();
                                $ifExistsAppointment = $patient->appointments()
                                                        ->where('patient_id', $patient->id)
                                                        ->where('date', $presentDate)
                                                        ->get();

                                //return $ifExistsAppointment;

                                // Validar si ya tiene cita asignada para el dia de hoy, solo es posible una cita al dia
                                if (count($ifExistsAppointment) > 0 )
                                {   
                                    // si ya tiene cita asignada solo se le compartira los datos para saber su cita asignada para el dia
                                    $MyAppointments = $patient->appointments()->where('patient_id', $patient->id)->first();
                                } else {
                                    // si no tiene cita para el dia de hoy se le asigna una nueva
                                    $string = $request->post('content');
                                    $magicWord = 'cita';
                                    $cleanContent = strtolower(str_replace(' ', '', $string));
                                    error_log(__LINE__ . __METHOD__ . ' encontro palabra cita --->' . var_export($cleanContent, true));
    
                                        if (str_contains($string, $magicWord)) {
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

                                            // Enviar email con datos de la cita asignada al paciente
                                            $appointmentTemp = DB::table('appointments')->where('patient_id', $patient->id)->first(['date','start_time']);
                                        
                                            $carbon = Carbon::parse();
                                            $mailData = [
                                                'title' => $patient->full_name,
                                                'body' => 'el día ' . $carbon->toDateString($appointmentTemp->date) . ' y hora ' . $carbon->isoFormat($appointmentTemp->start_time, 'h:mm A')
                                            ];
                                             
                                            Mail::to($patient->email)->send(new DemoMail($mailData));
                                        }
                                }

        // Condicionar asistente para darle contexto
        $context = [
            "role" => "system",
            "content" => ContextAISystem::CONTEXT_SYSTEM .
                         "- Esta es la informacion o datos personales del USUARIO {$patient} .
                          - Esta es la informacion de la cita asignada del USUARIO {$MyAppointments}.
                          - Informa al USUARIO la cita agendada que se le asigno {$MyAppointments}, si te pregunta, solo le diras estos datos 'FECHA' de la cita, 'HORA' solamente la hora se la diras en formato de 12 horas.",
        ];
        // Agregar el contexto inicial al arreglo de mensajes
        $messages = [$context];

                while(true) {
                // Obtener el contenido actual del archivo JSON
                $filePath = public_path('conversation.json');
                //$messages = json_decode(File::get($filePath), true);

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
                    "max_tokens" => 150 
                ])->json();

                //error_log(__LINE__ . __METHOD__ . ' chat --->' . var_export($response, true));

                $response_content = $response['choices'][0]['message']['content'];

                $messages[] = ["role" => "assistant", "content" => $response_content];

               /*  $storeChat = DB::table('chats')->insert([
                    'chat_info'  =>  $response_content,
                    'user_info'  =>  $content,
                    'patient_id' => $patient->id,
                    'created_at' => now('America/Bogota'),
                    'updated_at' => now('America/Bogota')
            ]); */

                // Guardar el contenido actualizado en el archivo JSON
                File::put($filePath, json_encode($messages));

                return response()->json($response_content);
            }
    }
}
