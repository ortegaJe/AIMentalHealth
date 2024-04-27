<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Remision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RemisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // current doctor ID
        $doctor_id = Auth::user()->id;

        $patient = Patient::find($request->patient_id);
        $patient->remisions()->create(
            [
                'content' => $request->content,
                'user_id' => $doctor_id
            ]
        );
        return back()
        ->with(
            'success',
            'a new remision is created'
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $remision = Remision::find($id);
        $patient = $remision->patient;
        return view('remisions.print', ['remision' => $remision, 'patient' => $patient]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
