<?php

declare(strict_types=1);

use App\Enums\Plan as PlanEnum;
use App\Models\Plan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('stripe_id')->unique();
            $table->string('name');
            $table->text('description');
            $table->json('features')->nullable();
            $table->json('abilities')->nullable();
        });

        collect($this->testPlans())->each(fn (array $plan) => Plan::create($plan));
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function testPlans(): array
    {
        return [
            [
                'stripe_id' => 'prod_rand1',
                'name' => PlanEnum::BASIC,
                'description' => 'Piano Gratis',
                'features' => ['2 Valutazioni al mese'],
                'abilities' => ['create-evaluations' => 2],
            ],
            [
                'stripe_id' => 'prod_rand2',
                'name' => PlanEnum::PRO,
                'description' => 'A long description',
                'features' => ['10 Valutazioni al mese'],
                'abilities' => ['create-evaluations' => 10],
            ],
            [
                'stripe_id' => 'prod_rand3',
                'name' => PlanEnum::UNLIMITED,
                'description' => 'A longer description',
                'features' => ['Valutazioni illimitate'],
                'abilities' => ['create-evaluations' => 0],
            ],
        ];
    }
};
