<?php

declare(strict_types=1);

arch()->preset()->php();

arch()->preset()->security();

arch()->preset()->laravel();

arch()
    ->expect('App\Actions')
    ->toBeClasses()
    ->toExtendNothing()
    ->toImplementNothing()
    ->not->toHavePublicMethodsBesides(['__construct', 'handle']);
