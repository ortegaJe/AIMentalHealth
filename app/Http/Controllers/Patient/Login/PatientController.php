<?php

namespace App\Http\Controllers\Patient\Login;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{

    public function login()
    {
        return view('auth.patient.login');
    }

    public function authenticate(Request $request)
    {
         //    handel POST /login
         $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        //return dd(Auth::guard('patients')->attempt($credentials));

        if (Auth::guard('patient')->attempt($credentials)) {
            $request->session()->regenerate();
            $patient_id = Auth::guard('patient')->user()->id;
            $patient = DB::table('question_patient')->where('patient_id', $patient_id)->exists();
    
            if ($patient) {
                //return 'La autenticación fue exitosa';
                return redirect()->route('patient.home');
            } else {
                //$patient_id = Auth::guard('patient')->user()->id;
                $patient = Patient::where('id', $patient_id)->first('id');
                return redirect()->route('questions', ['patient' => $patient]);
            }

        } else {
            // La autenticación falló
            return back()->withErrors(['message' => 'Credenciales inválidas'])->withInput();
        }
    }

    public function home()
    {
        return view('patient-home.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('patient.login')
            ->with('success', 'You are logged out.');
    }
}
