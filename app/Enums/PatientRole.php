<?php
namespace App\Enums;

enum PatientRole: int
{
    case PATIENT = 3;

    public static function values(): array
    {
        return array_column(self::cases(), 'value','name');
        // ["deposit" => "Deposit", "withdraw" => "Withdraw"]
    }

    public static  function isPatient( PatientRole $patientRole)
    {
        return $patientRole->value == PatientRole::PATIENT->value;
    }
}