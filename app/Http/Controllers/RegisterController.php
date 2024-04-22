<?php

namespace App\Http\Controllers;

use App\Mail\CredentialMail;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use App\Models\Patient;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected function index()
    {
        return view('auth.patient.register');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function store(Request $request)
    {
        $request->validate([
            'identification' => ['required', 'unique:patients'],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:patients'],
            'phone' => ['required', 'string'],
            'dob' => ['required', 'date'],
        ]);

        $realPassword = fake()->regexify('[A-Z]{5}[0-4]{3}');

        $patient = Patient::create([
            'identification' => $request->identification,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'dob' => $request->dob,
            'token' => fake()->uuid(),
            'password' => hash::make($realPassword),
        ]);

        //return $patient;

        $mailData = [
            'full_name' => $patient->full_name,
            'email' => $patient->email,
            'password' => $realPassword,
        ];

        //3jYUh8s4TG8Qphr
        //your.email+fakedata26705@gmail.com
        //YJTAN000
            
        Mail::to($patient->email)->send(new CredentialMail($mailData));

        return redirect()
        ->route('patient.login')
        ->with(
            'success', 'Hola! ' . $patient->full_name . ' Te enviamos un correo con tus credenciales de ingreso. '
        );
    }
}
