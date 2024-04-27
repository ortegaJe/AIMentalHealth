<?php

namespace App\Http\Controllers\Patient\Home;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Remision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        return view('patient-home.index', compact('appointments', 'remisions'));
    }
}
