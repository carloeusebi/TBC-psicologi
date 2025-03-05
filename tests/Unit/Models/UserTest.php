<?php

declare(strict_types=1);

use App\Models\Evaluation;
use App\Models\Patient;
use App\Models\User;

it('has many patients', function () {
    $user = User::factory()->create();
    Patient::factory(3)->for($user)->create();

    expect($user->patients)->tohaveCount(3)
        ->each->toBeInstanceOf(Patient::class);
});

it('has many evaluations', function () {
    $user = User::factory()->create();
    Patient::factory()->for($user)->hasEvaluations(3)->create();

    expect($user->evaluations)->toHaveCount(3)
        ->each->toBeInstanceOf(Evaluation::class);
});
