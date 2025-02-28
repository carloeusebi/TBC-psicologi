<?php

declare(strict_types=1);

use App\Console\Commands\FetchPlansCommand;
use Laravel\Cashier\Cashier;

use function Pest\Laravel\artisan;
use function Pest\Laravel\assertDatabaseCount;

test('stores plans from stripe api', function () {
    artisan(FetchPlansCommand::class)?->assertOk();

    $prices = collect(Cashier::stripe()->prices->all()->data);
    $plans = collect(Cashier::stripe()->products->all(['active' => true])->data);

    assertDatabaseCount('plans', $plans->count());
    assertDatabaseCount('prices', $prices->count());
});
