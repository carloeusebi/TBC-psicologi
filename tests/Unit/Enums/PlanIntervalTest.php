<?php

declare(strict_types=1);

use App\Enums\PriceInterval;

test('all cases have a label', function () {
    collect(PriceInterval::cases())->each(function (PriceInterval $interval) {
        expect($interval->label())->not()->toBeNull();
    });
});
