<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

                // insertar users 
                $users = [
                    [
                        'name' => 'Profesional01',
                        'lastname' => 'Profesional01_lastname',
                        'username' => 'Profesional01_username',
                        'email' => 'profesional01@mentalhealth.ai.com',
                        'role' => 0,
                        'password' => Hash::make('123456'),
                    ],
                    [
                        'name' => 'Profesional02',
                        'lastname' => 'Profesional02_lastname',
                        'username' => 'Profesional02_username',
                        'email' => 'profesional02@mentalhealth.ai.com',
                        'role' => 0,
                        'password' => Hash::make('123456'),
                    ],
                    [
                        'name' => 'Admisiones',
                        'lastname' => 'Admisiones_lastname',
                        'username' => 'Admisiones_username',
                        'email' => 'admisiones@mentalhealth.ai.com',
                        'role' => 1,
                        'password' => Hash::make('123456'),
                    ],
                    [
                        'name' => 'Admin',
                        'lastname' => 'App',
                        'username' => 'Admin_App',
                        'email' => 'admin@mentalhealth.ai.com',
                        'role' => 2,
                        'password' => Hash::make('123456'),
                    ],
        
                ];
                foreach ($users as $key => $user) {
                    User::create($user);
                }
        
                // insertar paticientes
                \App\Models\Patient::factory(5)->create();
        
                // llenar  doctor_patient tabla
                $data = [
                    ['patient_id' => 1, 'user_id' => fake()->numberBetween(1, 2)],
                    ['patient_id' => 2, 'user_id' => fake()->numberBetween(1, 2)],
                    ['patient_id' => 3, 'user_id' => fake()->numberBetween(1, 2)],
                    ['patient_id' => 4, 'user_id' => fake()->numberBetween(1, 2)],
                    ['patient_id' => 5, 'user_id' => fake()->numberBetween(1, 2)],
                ];
                DB::table('doctor_patient')->insert($data);

                // insertar Citas 
                \App\Models\Appointment::factory(5)->create();
    }
}
