<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PriceInterval;
use App\Models\Plan;
use App\Models\Price;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Price>
 */
final class PriceFactory extends Factory
{
    protected $model = Price::class;

    public function definition(): array
    {
        return [
            'plan_id' => Plan::factory(),
            'stripe_id' => fake()->word(),
            'interval' => fake()->randomElement(PriceInterval::cases()),
            'label' => fake()->word(),
            'amount' => (float) fake()->numberBetween(0, 20),
        ];
    }
}
