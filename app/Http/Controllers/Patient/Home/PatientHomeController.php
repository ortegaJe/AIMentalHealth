<?php

namespace App\Http\Controllers\Patient\Home;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
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
        // A list of doctor-patient appointments
        $appointments = Appointment::join('users', 'users.id', 'appointments.user_id')
                                        ->join('patients', 'patients.id', 'appointments.patient_id')
                                        ->where('appointments.patient_id', $patient->id)->get();
                                        
                                        //return $appointments;

        return view('patient-home.index', compact('appointments'));
    }
}
