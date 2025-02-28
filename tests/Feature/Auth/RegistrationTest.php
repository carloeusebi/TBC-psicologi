<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

test('registration screen can be rendered', function () {
    $response = get(route('register'));

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = post(route('register'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'Pa$$w0rd!',
        'password_confirmation' => 'Pa$$w0rd!',
    ]);

    assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});
