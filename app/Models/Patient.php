<?php

namespace App\Models;

use App\Enums\PatientRol;
use App\Enums\PatientRole;
use App\Enums\UserRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Patient as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Patient extends Authenticatable
{
    use HasFactory, Notifiable;
    
    protected $table = 'patients';
    protected $guard = 'patient';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'identification',
        'age',
        'address',
        'neighborhood',
        'city',
        'program_id',
        'cuatrimestre',
        'dob',
        'phone',
        'email',
        'comments',
        'antecedents',
        'role',
        'token',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'role'=>PatientRole::class,
    ];

    public function scans()
    {
        return $this->hasMany(Scan::class);
    }

    public function orientationLtrs()
    {
        return $this->hasMany(OrientationLetter::class);
    }

    public function appointments()
    {
    return $this->hasMany(Appointment::class);
    }
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
    
    /**
     * The doctors that belong to the Patient
     */
    public function doctors()
    {
        return $this->belongsToMany(User::class, 'doctor_patient', 'patient_id', 'user_id');
    }
}
