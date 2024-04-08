<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'identification' => fake()->numberBetween(10, 20),
            'full_name' => fake()->name(). '' . fake()->lastName(),
            'age' => fake()->numberBetween(18, 28),
            'dob' => fake()->date('Y_m_d'),
            'address' => fake()->address(),
            'neighborhood' => fake()->text(),
            'city' => fake()->city(),
            'program_id' => fake()->numberBetween(1, 21),
            'cuatrimestre' => fake()->numberBetween(1, 5),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'antecedents' => fake()->sentence(),
            'comments' => fake()->paragraph(),
        ];
    }
}
