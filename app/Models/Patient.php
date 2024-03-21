<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;



class Patient extends Model
{
    use HasFactory, HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'lastname',
        'identification',
        'dob',
        'phone',
        'email',
        'diseases',
        'allergies',
        'background',
    ];

    public function appointments()
    {
    return $this->hasMany(Appointment::class);
    }
    
    /**
     * The doctors that belong to the Patient
     */
    public function doctors()
    {
        return $this->belongsToMany(User::class, 'doctor_patient', 'patient_id', 'user_id');
    }
}
