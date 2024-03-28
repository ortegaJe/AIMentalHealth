<?php

namespace App\Http\Controllers;

use App\Helpers\ModelHelpers;
use App\Http\Requests\PatientFormRequest;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


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

        return $patients;
    }

    // find the  patients whose  names or last name match the query provided  
    public function findByQuery(Request $request)
    {
        $result = Patient::select('id', DB::raw("CONCAT(patients.name,' ',patients.lastname) as text"))
            ->where(
                'lastname',
                'LIKE', '%' . request('queryTerm') . '%'
            )
            ->orWhere(
                'name',
                'LIKE', '%' . request('queryTerm') . '%'
            )
            ->get(
            );
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
    public function store(Request $request)
    {
        //$validated = $request->validated();
        $patient = Patient::create($request->all());

        // If this patient was added by the doctor 
        // we attachPatient to the current doctor
        if (isset($request->doctor_id)) {
            ModelHelpers::attachPatient($request->doctor_id, $patient->id);

            return ["Result" => "Data has been saved", "Data" => $patient];
        }

        return ["Result" => "Data has been saved", "Data" => $patient];

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Patient $patient)
    {

        // current doctor ID
        //$doctor_id = Auth::user()->id;

        // A list of doctor-patient appointments
        $appointments = $patient->appointments()->get();

        // A list of doctor-patient orientationLtrs
        $orientationLtrs = $patient->orientationLtrs()->get();
        
        // A list of doctor-patient prescriptions
        $prescriptions = $patient->prescriptions()->get();
        
        // A list of doctor-patient scans
        $scans = $patient->scans()->get();

        $data = [
            'patient' => $patient,
            'appointments' => $appointments,
            'prescriptions'=>$prescriptions,
            'scans'=>$scans,
            'orientationLtrs'=>$orientationLtrs,
        ];

        return $data;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Patient $patient)
    {
        return view('patients.edit', ['patient' => $patient]);
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
        $patient->update($validated);

        return back()
        ->with(
                'success', 'patients: ' . $patient->name . ' is updated! '
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