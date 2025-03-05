<?php

declare(strict_types=1);

use App\Models\Plan;
use App\Models\Price;

it('has many prices', function () {
    expect(Plan::firstOrFail()->prices)
        ->each
        ->toBeInstanceOf(Price::class);
});
