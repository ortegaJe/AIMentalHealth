<?php

namespace App\Http\Controllers\Patient\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PatientHomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:patients');
    }
    
    public function index()
    {
        return view('home.patient');
    }
}
