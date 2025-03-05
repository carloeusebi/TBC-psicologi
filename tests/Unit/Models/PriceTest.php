<?php

declare(strict_types=1);
use App\Models\Plan;
use App\Models\Price;

it('belongs to a plan', function () {
    expect(Price::firstOrFail()->plan)->toBeInstanceOf(Plan::class);
});
