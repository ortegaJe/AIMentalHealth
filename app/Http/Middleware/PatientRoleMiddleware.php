<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $patient = Auth::user()->role->value;

         //dd( UserRoles::values()[$roles]);
        // check & verify with route
        if (Auth::check($patient)) {
            
            return $next($request);
        }
        // TODO to be changed to 401 Unauthorized 
        return response()->json(['You do not have permission to access for this page.']);
    }
}
