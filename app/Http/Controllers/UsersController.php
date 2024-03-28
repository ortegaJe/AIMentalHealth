<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserFormRequest;
use App\Http\Requests\UserUpdateFormRequest;
use App\Models\User;
use App\Enums\UserRoles;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;



class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        
        return $users;
    }

    /**
     * find the  doctors whose  names or last name match the query provided  
     **/
    public function findByQuery(Request $request)
    {
        $result = User::select('id', DB::raw("CONCAT(users.name,' ',users.lastname) as text"))
            ->where('role', UserRoles::DOCTOR->value)
            ->where(function($query) {
                $query ->where('lastname', 'LIKE', '%' . request('queryTerm') . '%')
                ->orWhere('name', 'LIKE', '%' . request('queryTerm') . '%');
            })
            ->get();
            // select id , concat(name,'_',lastname) from users where role  = 0  and  (name = queryTerm or lastname = queryTerm )

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
    public function store(UserFormRequest $request)
    {
        $validated = $request->validated();

        // hash password
        $validated['password'] = Hash::make($validated['password']);

        // store the VALIDATED user info to the database
        $user = User::create($validated);

        return ["Result" => "Data has been saved", "Data" => $user];
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        // TODO check if $this  the update authorization 
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateFormRequest $request, User $user)
    {
        // TODO check if $this  the update authorization 

        $validated = $request->validated();
        $user->update($validated);

        return ["Result" => "Data has been updated"];

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // TODO check if $this has the delete authorization 

        $user->delete();

        return ["Result" => "Data has been deleted"];

    }
}