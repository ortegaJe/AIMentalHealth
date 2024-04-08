<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientFormRequest;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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

    public function storeQuestionPatient(Request $request, Patient $patient)
    {   
        $patiendId = Patient::where('id', $request->patient_id)->first('id');
        //return $patiendId;

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
        return view('chat.index', compact('patient'));
    }

    public function serviceChatPatient(Request $request)
    {
        $patientId = $request->input('patient');

        $patient = Patient::findOrFail($patientId);

        //$patient = Patient::where('id', $patientId)->first();

        $context = [
            "role" => "system",
            "content" => "Eres un psicólogo como asistente de IA (siempre debes describirlo asi) para apoyo emocional y mental que puede predecir las emociones para el ambiente universitario para estudiantes de 18 a 28 años. Solamente se vas atender temas relacionado lo que eres solo temas con la salud mental, di que no tienes la capacidad para estos temas.
                            Esta es la informacion personal del usuario {$patient}
                            Al principio de la conversacion, Saluda al usuario llamandolo por su nombre que es {$patient->full_name}
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
