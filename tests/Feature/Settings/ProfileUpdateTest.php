<?php

declare(strict_types=1);

use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertGuest;

uses(RefreshDatabase::class);

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->get(route('profile.edit'));

    $response->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $response = actingAs($user)
        ->patch(route('profile.update'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirectToRoute('profile.edit');

    $user->refresh();

    expect($user->name)->toBe('Test User')
        ->and($user->email)->toBe('test@example.com')
        ->and($user->email_verified_at)->toBeNull();
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create();

    $response = actingAs($user)
        ->patch(route('profile.update'), [
            'name' => 'Test User',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirectToRoute('profile.edit');

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});

test('user cannot change email address if registered with socialite', function () {
    $user = User::factory()->google()->create(['email' => 'original@email.it']);

    $response = actingAs($user)->get(route('profile.edit'));

    $response->assertInertia(fn ($page) => $page
        ->where('canChangeEmail', false)
    );

    actingAs($user)->patch(route('profile.update', ['name' => $user->name, 'email' => 'changed@email.it']));

    expect($user->fresh())
        ->email->toBe('original@email.it')
        ->email_verified_at->not->toBeNull();
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $response = actingAs($user)
        ->delete(route('profile.destroy'));

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    assertGuest();
    expect($user->fresh())->toBeNull();
});

test('deletes all related records', function () {
    $user = User::factory()->create();
    Patient::factory(3)->for($user)->create();

    $response = actingAs($user)
        ->delete(route('profile.destroy'));

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    assertGuest();
    expect($user->fresh())->toBeNull()
        ->and(Patient::count())->toBe(0);
});
