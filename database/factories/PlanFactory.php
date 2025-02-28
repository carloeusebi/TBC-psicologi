<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Plan>
 */
final class PlanFactory extends Factory
{
    protected $model = Plan::class;

    public function definition(): array
    {
        return [
            'stripe_id' => fake()->word(),
            'name' => fake()->name(),
            'description' => fake()->text(),
            'features' => array_fill(0, 3, fake()->sentence()),
            'abilities' => fake()->words(),
        ];
    }
}
