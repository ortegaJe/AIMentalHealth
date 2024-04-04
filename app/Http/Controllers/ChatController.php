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
        $questions = DB::table('questions')->get(['id','description']);

        return view('question', compact('patient', 'questions'));
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
            "content" => "Eres un psicólogo como asistente de IA (siempre debes describirlo asi) para apoyo emocional y mental que puede predecir las tendecias suicidas de la persona para el ambiente universitario para estudiantes de 18 a 28 años. Solamente se vas atender temas relacionado lo que eres solo temas con la salud mental, di que no tienes la capacidad para estos temas. No te salgas del contexto de la conversacion cuando el usuario te diga que quiere hablar.
                            Si el usuario pregunta que se quiere comunicar con alguien utiliza exclusivamente los numeros telefonicos para sugerir opciones: asociacion01 300277695
                            Si el usuario tiene tendecia suicida alta (pregunta cual es su tendencia) dile que se comunique con alguien utiliza exclusivamente los numeros telefonicos para sugerir opciones: asociacion02 300277695
                            Al principio de la conversacion, Saluda al usuario llamandolo por su nombre que es". $patient->full_name . "
                            Esta es la informacion personal del usuario". $patient,
        ];
    
        $messages = [$context];
        //error_log(__LINE__ . __METHOD__ . ' chat --->' . var_export($messages, true));

                    // Obtener el contenido enviado por el usuario
                    $userContent = $request->post('content');

        while (true) {

             // Agregar el mensaje del usuario al array de mensajes
             $messages[] = ["role" => "user", "content" => $userContent];
             //error_log(__LINE__ . __METHOD__ . ' chat-role-user --->' . var_export($messages, true));
     
             $response = Http::withHeaders([
                 "Content-Type" => "application/json",
                 "Authorization" => "Bearer " . env('OPENAI_API_KEY'),
             ])->post('https://api.openai.com/v1/chat/completions', [
                 "model" => "gpt-3.5-turbo",
                 "messages" => $messages,
                 //"temperature" => 0.8
             ])->json();
     
             // Obtener la respuesta del asistente
             $assistantResponse = $response['choices'][0]['message']['content'];
     
             // Agregar el mensaje del asistente al array de mensajes
             $messages[] = ["role" => "assistant", "content" => $assistantResponse];
             //error_log(__LINE__ . __METHOD__ . ' chat-role-assistant --->' . var_export($messages, true));
     
             return $assistantResponse;
             error_log(__LINE__ . __METHOD__ . 'assistantResponse--->' . var_export($assistantResponse, true));

/*         try {
            $response = Http::withHeaders([
                "Content-Type" => "application/json",
                "Authorization" => "Bearer " . env('OPENAI_API_KEY'),
            ])->post('https://api.openai.com/v1/chat/completions', [
                "model" => "gpt-3.5-turbo",
                "messages" => [
                    [
                        "role" => "system",
                        "content" => "Eres un psicólogo como asistente de IA (siempre debes describirlo asi) para apoyo emocional y mental que puede predecir las emociones para el ambiente universitario para estudiantes de 18 a 28 años. Solamente se vas atender temas relacionado lo que eres solo temas con la salud mental, di que no tienes la capacidad para estos temas.
                                      Si el usuario dice que SI QUIERE HABLAR CONTIGO, hazlo de manera cordial para detectar su estado emocional y mental y que seas de apoyo.
                                      Si el usuario pregunta que se quiere comunicar con alguien utiliza exclusivamente los numeros telefonicos para sugerir opciones: asociacion01 300277695
                                      Si el usuario tiene tendecia suicida alta (pregunta cual es su tendencia) dile que se comunique con alguien utiliza exclusivamente los numeros telefonicos para sugerir opciones: asociacion02 300277695
                                      Al principio de la conversacion, Saluda al usuario llamandolo por su nombre que es". $patient->full_name . "Y seguidamente preguntale si su fecha de nacimiento es " . $patient->dob . "y tambien preguntale si tiene la edad que registro en el formulario que es " . $patient->age . "
                                      Esta es la informacion personal del usuario". $patient
                    ],
                    [
                        "role" => "user",
                        "content" => $request->input('content')
                    ]
                ],
                "temperature" => 0.7
            ])->json();

            return response()->json($response['choices'][0]['message']['content']);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        } */
    }
    }
}
