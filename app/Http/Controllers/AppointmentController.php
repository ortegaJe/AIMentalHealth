<?php

namespace App\Http\Controllers;

use App\Enums\UserRoles;
use App\Http\Requests\AppointmentFormRequest;
use App\Mail\DemoMail;
use App\Models\Patient;
use App\Models\User;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use ModelHelpers;


class AppointmentController extends Controller
{
    /**
     * Display a listing of the Appointments for the current Doctor.
     *
     * @return \Illuminate\Http\Response
     */
    // $id
    public function index()
    {
        $appointments = [];

        // FIXME hide private appointment 
        // when we implement private patient(that are created by the doctor himself)

        // find all  appointment to secretary
        if (UserRoles::isSecretary(Auth::user()->role) || UserRoles::isAdmin(Auth::user()->role)) 
        {
            $appointments = Appointment::join('patients', 'patients.id', 'appointments.patient_id')
                                            ->select('patients.id as patient_id',
                                                         'patients.identification',
                                                         'patients.full_name',
                                                         'appointments.motivation',
                                                         'appointments.date',
                                                         'appointments.start_time',
                                                         'appointments.status',
                                                        DB::raw("LOWER(CASE WHEN appointments.motivation 
                                                                    IN('RIESGO BAJO', 'RIESGO MODERADO', 'RIESGO ALTO', 'PRECISA INGRESO') 
                                                                        THEN appointments.motivation ELSE '' END) as risk"))
                                                        ->orderbyDesc('appointments.date')->get();
        }
        // find all  appointment assigned to a doctor
        else if (UserRoles::isDoctor(Auth::user()->role))
        {
            $doctor = User::find(Auth::user()->id);
            $appointments = Appointment::join('users', 'users.id', 'appointments.user_id')
                                            ->join('patients', 'patients.id', 'appointments.patient_id')
                                                ->where('appointments.user_id', $doctor->id)
                                                    ->select('patients.id as patient_id',
                                                            'patients.identification',
                                                            'patients.full_name',
                                                            'appointments.motivation',
                                                            'appointments.date',
                                                            'appointments.start_time',
                                                            'appointments.status',
                                                            DB::raw("LOWER(CASE WHEN appointments.motivation 
                                                                        IN('RIESGO BAJO', 'RIESGO MODERADO', 'RIESGO ALTO', 'PRECISA INGRESO') 
                                                                            THEN appointments.motivation ELSE '' END) as risk"))
                                                        ->orderbyDesc('appointments.date')->get();
                                                        //return $appointments;
        }

        return view('appointments.index', ['appointments' => $appointments]);
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
    public function store(AppointmentFormRequest $request)
    {
        $patient = Patient::find($request->patient_id);

        $validated = $request->validated();

        $patient->appointments()->create(
            array_merge(
                $validated,
                ['user_id' => $request->doctor_id],
            )
        );

        // If a patient will have an appointment with a doctor 
        // we attachPatient to the current doctor
        ModelHelpers::attachPatient($request->doctor_id, $patient->id);

        // Enviar email con datos de la cita asignada al paciente
        $appointmentTemp = DB::table('appointments')->where('patient_id', $patient->id)->first(['date','start_time']);
        $date = Carbon::parse($appointmentTemp->date)->isoFormat('D-M-Y');
        $startTime = Carbon::parse($appointmentTemp->start_time)->format('g:i:s A');
        
        $mailData = [
            'title' => $patient->full_name,
            'body' => 'Fecha de la cita: ' . $date . ' - Hora de la cita ' . $startTime ,
        ];

        Mail::to($patient->email)->send(new DemoMail($mailData));


        return back()
            ->with('success', 'a new appointment is created');
            
    }

    public function addAppoPatient(AppointmentFormRequest $request)
    {
        $patient = Patient::find($request->patient_id);

        $validated = $request->validated();

        $patient->appointments()->create(
            array_merge(
                $validated,
                ['user_id' => $request->doctor_id],
            )
        );

        // If a patient will have an appointment with a doctor 
        // we attachPatient to the current doctor
        ModelHelpers::attachPatient($request->doctor_id, $patient->id);


        return back()
            ->with('success', 'a new appointment is created');
            
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
        
                $programs = $patient::join('programs', 'programs.id', 'patients.program_id')->get(['programs.id as id_program','programs.name as program']);
        
                return view(
                    'appointments.index',
                    [
                        'patient' => $patient,
                        'appointments' => $appointments,
                        'prescriptions'=>$prescriptions,
                        'scans'=>$scans,
                        'orientationLtrs'=>$orientationLtrs,
                        'remisions' => $remisions,
                        'programs' => $programs,
                    ]
                );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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