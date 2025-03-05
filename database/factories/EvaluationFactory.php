<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Evaluation;
use App\Models\Patient;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Evaluation>
 */
final class EvaluationFactory extends Factory
{
    protected $model = Evaluation::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'has_introduction' => false,
            'introduction' => null,
            'hap_patient_form' => false,
            'completed_at' => null,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),

            'patient_id' => Patient::factory(),
        ];
    }

    public function withIntroduction(): self
    {
        return $this->state(fn (array $attributes): array => [
            'has_introduction' => true,
            'introduction' => fake()->paragraph(),
        ]);
    }

    public function withForm(): self
    {
        return $this->state(fn (array $attributes): array => [
            'hap_patient_form' => true,
        ]);
    }

    public function completed(): self
    {
        return $this->state(fn (array $attributes): array => [
            'completed_at' => CarbonImmutable::now(),
        ]);
    }
}
