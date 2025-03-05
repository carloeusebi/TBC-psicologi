<?php

declare(strict_types=1);
use App\Models\Evaluation;
use App\Models\Patient;
use App\Models\User;

it('belongs to a user', function () {
    $evaluation = Evaluation::factory()->create();

    expect($evaluation->user)->toBeInstanceOf(User::class);
});

it('belongs to a patient', function () {
    $evaluation = Evaluation::factory()->create();

    expect($evaluation->patient)->toBeInstanceOf(Patient::class);
});
