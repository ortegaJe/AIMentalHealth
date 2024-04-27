<?php

namespace App\Http\Controllers;

use App\Helpers\ModelHelpers;
use App\Http\Requests\PatientFormRequest;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PatientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patients = Patient::orderBy('id')->get();

        return view('patients.index', ['patients' => $patients]);
    }

    // find the  patients whose  names or last name match the query provided  
    public function findByQuery(Request $request)
    {
        $result = Patient::select('id', DB::raw("CONCAT(patients.full_name) as text"))
            ->where(
                'full_name',
                'LIKE', '%' . request('queryTerm') . '%'
            )
            ->orWhere(
                'full_name',
                'LIKE', '%' . request('queryTerm') . '%'
            )
            ->get();
        return response()->json($result);
    }

    public function findByQueryProgram(Request $request)
    {
        $result = DB::table('programs')->select('id', DB::raw("name as text"))
            ->where(
                'name',
                'LIKE', '%' . request('queryTerm') . '%'
            )
            ->get();
        return response()->json($result);
    }

    public function findByQueryPatientAddAppo(Request $request, Patient $patient)
    {
        $result = DB::table('patients')->select('id', DB::raw("full_name as text"))
            ->where(
                'full_name',
                'LIKE', '%' . request('queryTerm') . '%'
            )
            ->where('patients.id', $patient)
            ->get();

        return response()->json($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PatientFormRequest $request)
    {
        $validated = $request->validated();
        $patient = Patient::create($validated);

        // If this patient was added by the doctor 
        // we attachPatient to the current doctor
        if (isset($request->doctor_id)) {
            ModelHelpers::attachPatient($request->doctor_id, $patient->id);

            return redirect()
                ->route(
                    'patients.show',
                    ['patient' => $patient]
                )
                ->with(
                    'success', 'patients: ' . $patient->name . ' is created '
                );
        }

        return redirect()
            ->route(
                'patients.index'
            )
            ->with(
                'success', 'patients: ' . $patient->name . ' is created '
            );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Patient $patient)
    {
        // Obtener la ruta del archivo JSON
        $folderPath = 'public/chats/'.$patient->token.'/';
        $fileName = 'chat.json';
        $filePath = $folderPath . $fileName;

        // Variable para almacenar los mensajes
        $messagesHtml = '';

        // Verificar si el archivo existe
        if (Storage::exists($filePath)) {
            // Leer el contenido del archivo JSON
            $jsonContent = Storage::get($filePath);

            // Decodificar el contenido JSON en un array PHP
            $messages = json_decode($jsonContent, true);

            // Utilizar el array $messages en tu consulta o en cualquier parte de tu aplicación
            // Recorrer el array para mostrar cada mensaje
            foreach ($messages as $message) {
                $role = $message['role'];
                $content = $message['content'];
                $date = $message['date'];

                ($role === 'user') ? $roleName = '<span class="badge badge-primary"><i class="nav-icon fas fa-user"></i> Estudiante</span>' : 
                    (($role === 'assistant') ? $roleName = '<span class="badge badge-success"><i class="nav-icon fas fa-robot"></i> AVi ChatBot</span>' : 
                        'rol no definido');

                //echo "Role: $role, Content: $content <br>";
                // Construir la respuesta HTML
                $messagesHtml .= "$roleName: $content <br> <span class='text-muted'>$date</span> <br>";
            }
        } else {
            //echo "El archivo JSON no existe.";
            //$messagesHtml = "El archivo JSON no existe.";
        }

        // current doctor ID
        $doctor_id = Auth::user()->id;

        // A list of doctor-patient appointments
        $appointments = $patient->appointments()->where('user_id', $doctor_id)->get();

        // A list of doctor-patient orientationLtrs
        $orientationLtrs = $patient->orientationLtrs()->where('user_id', $doctor_id)->get();

        // A list of doctor-patient remisions
        $remisions = $patient->remisions()->where('user_id', $doctor_id)->get();
        
        // A list of doctor-patient prescriptions
        $prescriptions = $patient->prescriptions()->where('user_id', $doctor_id)->get();
        
        // A list of doctor-patient scans
        $scans = $patient->scans()->where('user_id', $doctor_id)->get();

        $programs = $patient::join('programs', 'programs.id', 'patients.program_id')
                                ->where('patients.id', $patient->id)
                                    ->get(['programs.id as id_program','programs.name as program']);

        return view(
            'patients.show',
            [
                'patient' => $patient,
                'appointments' => $appointments,
                'prescriptions'=>$prescriptions,
                'scans'=>$scans,
                'orientationLtrs'=>$orientationLtrs,
                'remisions' => $remisions,
                'programs' => $programs,
                'messagesHtml' => $messagesHtml
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Patient $patient)
    {
        $programs = $patient::join('programs', 'programs.id', 'patients.program_id')
                                ->where('patients.id', $patient->id)
                                    ->get(['programs.id as id_program','programs.name as program']);

        return view('patients.edit', ['patient' => $patient, 'programs' => $programs]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Patient $patient, PatientFormRequest $request)
    {
        $validated = $request->validated();
        //return $validated;
        $patient->update($validated);

        //return $patient;
        return back()
        ->with(
                'success', 'patients: ' . $patient->full_name . ' is updated! '
            );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}