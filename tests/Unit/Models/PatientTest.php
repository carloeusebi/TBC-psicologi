<?php

declare(strict_types=1);

use App\Models\Evaluation;
use App\Models\Patient;
use App\Models\User;

it('belongs to a user', function () {
    $patient = Patient::factory()->create();

    expect($patient->user)->toBeInstanceOf(User::class);
});

it('has many evaluations', function () {
    $patient = Patient::factory()->hasEvaluations(3)->create();

    expect($patient->evaluations)->toHaveCount(3)
        ->each->toBeInstanceOf(Evaluation::class);
});
