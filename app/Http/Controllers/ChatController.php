<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientFormRequest;
use App\Http\Requests\QuestionFormRequest;
use App\Models\Patient;
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

/*         $total = 4;
        $riskStatus = DB::table('question_patient as a')
                    ->select(
                        DB::raw("CASE WHEN SUM(a.confirmed) <= 2 THEN 'RIESGO BAJO' 
                                        WHEN SUM(a.confirmed) <= 4 THEN 'RIESGO MODERADO'
                                            WHEN SUM(a.confirmed) <= 6 THEN 'RIESGO ALTO'
                                                WHEN SUM(a.confirmed) <= 8 THEN 'PRECISA INGRESO'
                                                    ELSE 'SIN PUNTUACIÓN'
                                                        END as riesgo")
                        )->where('a.patient_id', $patient->id)->pluck('riesgo');

        return $riskStatus; */

        $total = 5;
        $riskStatus = DB::table('question_patient as a')
                            ->select(
                                DB::raw("CASE WHEN $total <= 2 THEN 'RIESGO BAJO' 
                                                WHEN $total <= 4 THEN 'RIESGO MODERADO'
                                                    WHEN $total <= 6 THEN 'RIESGO ALTO'
                                                        WHEN $total <= 8 THEN 'PRECISA INGRESO'
                                                            ELSE 'SIN PUNTUACIÓN'
                                                                END as riesgo")
                                )->where('.patient_id', $patient->id)->pluck('riesgo')->take(1);

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


                                 if ($riskStatus === 'RIESGO BAJO') {
                                    return "$riskStatus y recomiendale agendar un cita con profesionales del plantel universitario que lo ayudaran mas con su estado.";
                                }
        
                                if ($riskStatus === 'RIESGO MODERADO') {
                                    return "$riskStatus y recomiendale agendar un cita con profesionales del plantel universitario que lo ayudaran mas con su estado.";
                                }

                                if ($riskStatus === 'RIESGO ALTO') {
                                    return "$riskStatus y recomiendale agendar un cita con profesionales del plantel universitario que lo ayudaran mas con su estado.";
                                }

                                if ($riskStatus === 'PRECISA INGRESO') {
                                    return "$riskStatus y recomiendale agendar un cita con profesionales del plantel universitario que lo ayudaran mas con su estado.";
                                }

                                //return $riskStatus;

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
                                )->where('a.patient_id', $patient->id)->pluck('riesgo')->take(1);

                                 if ($riskStatus === 'RIESGO BAJO') {
                                    //CONTEXTO DE CADA ESTADO DE RIESGO DEL PACIENTE
                                    return "Recomiendale agendar un cita con profesionales del plantel universitario que lo ayudaran mas con su estado.";
                                }
        
                                if ($riskStatus === 'RIESGO MODERADO') {
                                    return "Recomiendale agendar un cita con profesionales del plantel universitario que lo ayudaran mas con su estado.";
                                }

                                if ($riskStatus === 'RIESGO ALTO') {
                                    return "Recomiendale agendar un cita con profesionales del plantel universitario que lo ayudaran mas con su estado.";
                                }

                                if ($riskStatus === 'PRECISA INGRESO') {
                                    return "Recomiendale agendar un cita con profesionales del plantel universitario que lo ayudaran mas con su estado.";
                                }

                                error_log(__LINE__ . __METHOD__ . ' chat --->' . var_export($riskStatus, true));
                                //return $riskStatus;

        // Condicionar asistente para darle contexto
        $context = [
            "role" => "system",
            "content" => "Eres un psicólogo como asistente de IA (siempre debes describirlo asi) para apoyo emocional y mental que puede predecir las emociones para el ambiente universitario para estudiantes de 18 a 28 años. Solamente se vas atender temas relacionado con la salud mental.
                            Esta es la informacion personal del usuario {$patient}
                            Dile al usuario que presenta este riesgo {$riskStatus}
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
                    "temperature" => 0.7
                ])->json();

                error_log(__LINE__ . __METHOD__ . ' chat --->' . var_export($response, true));


                $response_content = $response['choices'][0]['message']['content'];

                $messages[] = ["role" => "assistant", "content" => $response_content];

                return response()->json($response_content);
        }
    }
}
