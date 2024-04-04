<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'full_name' => 'required',
            'identification' =>  'required',
            'age' => 'required',
            'address'  => 'required',
            'neighborhood' => 'required',
            'city' => 'required',
            'program_id' => 'required',
            'cuatrimestre' => 'required',
            'dob' =>  'required',
            'email' =>  'required',
            'phone' =>  'required',
            'antecedents' => 'nullable|string',
            'comments' => 'nullable|string',

        ];
    }
}
