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
            ->where('genders', Gender::options())
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

it('can store a patient', function () {
    $user = User::factory()->create();
    $data = Patient::factory()->withoutParents()->make();

    actingAs($user)->post(route('patients.store'), $data->toArray())
        ->assertRedirectToRoute('patients.show', $patient = Patient::firstOrFail())
        ->assertSessionHas('success', 'Paziente creato con successo.');

    expect($patient->user_id)->toBe($user->id);
});

it('defaults therapy start date to today', function () {
    $user = User::factory()->create();
    $data = Patient::factory()->withoutParents()->make(['therapy_start_date' => null]);

    actingAs($user)->post(route('patients.store'), $data->toArray())
        ->assertRedirectToRoute('patients.show', $patient = Patient::firstOrFail())
        ->assertSessionHas('success', 'Paziente creato con successo.');

    expect($patient->therapy_start_date->format('d/m/Y'))->toBe(today()->format('d/m/Y'));
});

it('show a patient', function () {
    $patient = Patient::factory()->create();

    actingAs($patient->user)->get(route('patients.show', $patient))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('genders', Gender::options())
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
        ->assertSessionHas('success', 'Paziente modificato con successo.');

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
        ->assertSessionHas('success', 'Paziente eliminato con successo.');

    expect(Patient::find($patient->id))->toBeNull();
});

it('can delete a patient with evaluations', function () {
    $patient = Patient::factory()->hasEvaluations(3)->create();

    actingAs($patient->user)->delete(route('patients.destroy', $patient))
        ->assertRedirectToRoute('patients.index')
        ->assertSessionHas('success', 'Paziente eliminato con successo.');

    expect(Patient::find($patient->id))->toBeNull();
});

it('deleting another user\'s patient resolves as a not found request', function () {
    $patient = Patient::factory()->create();

    actingAs(User::factory()->create())->delete(route('patients.destroy', $patient))
        ->assertNotFound();

    expect(Patient::find($patient->id))->not()->toBeNull();
});
