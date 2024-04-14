<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientFormRequest;
use App\Http\Requests\QuestionFormRequest;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Throwable;

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

    public function chatPatient(Patient $patient)
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

  /*                               $RISK_NAME = $riskStatus;

                                $RISK_TYPE = [
                                    $RISK_NAME => 'RIESGO BAJO',
                                    $RISK_NAME => 'RIESGO MODERADO',
                                    $RISK_NAME => 'RIESGO ALTO',
                                    $RISK_NAME => 'RIESGO PRECISA INGRESO',
                                ];

                                $RISK_DEFAULT = 'SIN PUNTUACION';

                                $RISK = $RISK_TYPE[$RISK_NAME] || $RISK_DEFAULT;

                                return $RISK; */

                                if ($riskStatus->riesgo === 'RIESGO ALTO' || $riskStatus->riesgo === 'PRECISA INGRESO') 
                                {
                                    $presentDate = now('America/Bogota')->toDateString();
                                    $ifExistsAppointment = $patient->appointments()
                                                            ->where('patient_id', $patient->id)
                                                            ->where('date', $presentDate)
                                                            ->get();

                                    //return $ifExistsAppointment;

                                    if (count($ifExistsAppointment) > 0 ) 
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
                                    }
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
                                    $newAppointment = $MyAppointments = $patient->appointments()->where('patient_id', $patient->id)->first();
                                } else {
                                    // si no tiene cita para el dia de hoy se le asigna una nueva
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
                                }

        // Condicionar asistente para darle contexto
        $context = [
            "role" => "system",
            "content" => "Eres un psicólogo como asistente de IA (siempre debes describirlo asi) para apoyo emocional y mental que puede predecir las emociones para el ambiente universitario para estudiantes de 18 a 28 años. Solamente se vas atender temas relacionado con la salud mental.
                            Esta es la informacion personal del usuario {$patient}
                            Dile al usuario que presenta este riesgo {$riskStatus->riesgo}
                            Ejecuta este codigo {$newAppointment} cuando el usuario te diga estas palabras [CITA, ASIGNAR CITA, QUIERO UNA CITA, PUEDES AYUDARME CON UNA CITA, QUIERO OTRA] agendar citas solo una sola vez el mismo dia, te penalizo si vuelves a realizar la cita el mismo dia, el usuario solo puede asignar una sola cita el mismo dia, para el dia siguiente si es posible asignar cita, solo le diras estos datos fecha de la cita, hora de inicio si es pm o am nunca lo menciones en formato de horas militares si no con el formato estandar de 12:00 horas. si ya tiene cita asignada le recuerdas.
                            Dile al usuario la cita agendada que se le asigno {$MyAppointments} solo le diras estos datos fecha de la cita, hora de inicio solamente. Si usas estas palabras tambien le diras [CUAL ES LA FECHA DE MI CITA, CUAL ES LA FECHA DE MI CITA AGENDA, CUAL ES LA FECHA] si ya tiene cita asignada le recuerdas.
                            Si el usuario pregunta que se quiere comunicar con alguien utiliza exclusivamente los numeros telefonicos para sugerir opciones: asociacion01 300277695
                            Si el usuario tiene tendecia suicida alta (pregunta cual es su tendencia) dile que se comunique con alguien utiliza exclusivamente los numeros telefonicos para sugerir opciones: asociacion02 300277695"
        ];
        // Agregar el contexto inicial al arreglo de mensajes
        $messages = [$context];

            while(true) {
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

                error_log(__LINE__ . __METHOD__ . ' chat --->' . var_export($response, true));


                $response_content = $response['choices'][0]['message']['content'];

                $messages[] = ["role" => "assistant", "content" => $response_content];

                return response()->json($response_content);
        }
    }
}
