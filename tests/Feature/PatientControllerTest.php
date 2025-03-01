<?php

declare(strict_types=1);

use App\Enums\Gender;
use App\Models\Patient;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;

it('list the patients', function () {
    $user = User::factory()->create();
    Patient::factory(5)->for($user)->create();

    actingAs($user)->get(route('patients.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('patients', 5)
        );
});

it('does not list other user\'s patients', function () {
    $user = User::factory()->create();
    Patient::factory(5)->for($user)->create();

    $otherUser = User::factory()->create();
    Patient::factory(5)->for($otherUser)->create();

    actingAs($user)->get(route('patients.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('patients', 5)
        );
});

it('renders the create page', function () {
    actingAs(User::factory()->create())->get(route('patients.create'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('genders')
            ->where('genders', Gender::options())
        );
});

it('can store a patient', function () {
    $user = User::factory()->create();
    $data = Patient::factory()->withoutParents()->make();

    actingAs($user)->post(route('patients.store'), $data->toArray())
        ->assertRedirectToRoute('patients.show', $patient = Patient::firstOrFail());

    expect($patient->user_id)->toBe($user->id);
});

it('show a patient', function () {
    $patient = Patient::factory()->create();

    actingAs($patient->user)->get(route('patients.show', $patient))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('patient.id', $patient->id)
            ->where('patient.first_name', $patient->first_name)
            ->where('patient.last_name', $patient->last_name)
            ->where('patient.name', $patient->name)
            ->where('patient.email', $patient->email)
            ->where('patient.age', $patient->age)
            ->where('patient.gender', $patient->gender)
            ->where('patient.gender_label', $patient->gender?->label())
            ->etc()
        );
});

it('can\'t see another user\'s patient', function () {
    $patient = Patient::factory()->create();

    actingAs(User::factory()->create())->get(route('patients.show', $patient))
        ->assertNotFound();
});

it('can update a patient', function () {
    $patient = Patient::factory()->create();
    $data = Patient::factory()->withoutParents()->make();

    actingAs($patient->user)->put(route('patients.update', $patient), $data->toArray())
        ->assertRedirectToRoute('patients.show', $patient)
        ->assertSessionHas('success', 'Paziente aggiornato.');

    expect($patient->refresh())
        ->first_name->toBe($data->first_name)
        ->last_name->toBe($data->last_name)
        ->email->toBe($data->email);
});

test('updating another user\'s patient resolves as a not found request', function () {
    $patient = Patient::factory()->create();
    $data = Patient::factory()->withoutParents()->make();

    actingAs(User::factory()->create())->put(route('patients.update', $patient), $data->toArray())
        ->assertNotFound();

    expect($patient->refresh())
        ->first_name->not()->toBe($data->first_name)->toBe($patient->first_name)
        ->last_name->not()->toBe($data->last_name)->toBe($patient->last_name)
        ->email->not()->toBe($data->email)->toBe($patient->email);
});

it('can delete a patient', function () {
    $patient = Patient::factory()->create();

    actingAs($patient->user)->delete(route('patients.destroy', $patient))
        ->assertRedirectToRoute('patients.index')
        ->assertSessionHas('success', 'Paziente eliminato.');

    expect(Patient::find($patient->id))->toBeNull();
});

it('deleting another user\'s patient resolves as a not found request', function () {
    $patient = Patient::factory()->create();

    actingAs(User::factory()->create())->delete(route('patients.destroy', $patient))
        ->assertNotFound();

    expect(Patient::find($patient->id))->not()->toBeNull();
});
