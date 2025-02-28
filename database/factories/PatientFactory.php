<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Gender;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Patient>
 */
final class PatientFactory extends Factory
{
    protected $model = Patient::class;

    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'gender' => fake()->randomElement(Gender::cases()),
            'birth_date' => fake()->dateTimeBetween('-100 years', '-18 years'),
            'birth_place' => fake()->word(),
            'address' => fake()->address(),
            'codice_fiscale' => fake()->word(),
            'therapy_start_date' => fake()->dateTimeBetween('-10 years'),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'weight' => fake()->randomNumber(2),
            'height' => fake()->randomNumber(2),
            'education' => fake()->word(),
            'job' => fake()->word(),
            'cohabitants' => fake()->word(),
            'drugs' => fake()->word(),

            'user_id' => User::factory(),
        ];
    }
}
