<?php

declare(strict_types=1);

use App\Models\Evaluation;
use App\Models\Patient;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;

it('list the evaluations', function () {
    $user = User::factory()->create();
    Patient::factory(5)->for($user)->hasEvaluations()->create();

    actingAs($user)->get(route('evaluations.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('evaluations', 5)
        );
});

it('does not list other user\'s evaluations', function () {
    $user = User::factory()->create();
    Patient::factory(5)->for($user)->hasEvaluations()->create();

    $otherUser = User::factory()->create();
    Patient::factory(5)->for($otherUser)->hasEvaluations()->create();

    actingAs($user)->get(route('evaluations.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('evaluations', 5)
        );
});

it('can store an evaluation', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->for($user)->create();
    $data = Evaluation::factory()->for($patient)->make();

    actingAs($user)->post(route('evaluations.store'), $data->toArray())
        ->assertSessionHasNoErrors()
        ->assertRedirect()
        ->assertRedirectToRoute('evaluations.show', $evaluation = Evaluation::firstOrFail())
        ->assertSessionHas('success', 'Valutazione creata con successo.');

    expect($evaluation->user->id)->toBe($user->id)
        ->and($evaluation->patient?->id)->toBe($patient->id);
});

it('cannot store an evaluation for another user\'s patient', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create();
    $data = Evaluation::factory()->for($patient)->make();

    actingAs($user)->post(route('evaluations.store'), $data->toArray())
        ->assertSessionHasErrors('patient_id');
});

it('show an evaluation', function () {
    $evaluation = Evaluation::factory()->create();

    actingAs($evaluation->user)->get(route('evaluations.show', $evaluation))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('evaluation', fn (Assert $page) => $page
                ->where('id', $evaluation->id)
                ->where('title', $evaluation->title)
                ->where('has_introduction', $evaluation->has_introduction)
                ->where('introduction', $evaluation->introduction)
                ->where('hap_patient_form', $evaluation->hap_patient_form)
                ->where('completed_at', $evaluation->completed_at?->toISOString())
                ->where('created_at', $evaluation->created_at->toISOString())
                ->where('updated_at', $evaluation->updated_at->toISOString())
            ));
});

it('can\'t see another user\'s evaluation', function () {
    Evaluation::factory()->create();

    actingAs(User::factory()->create())->get(route('evaluations.show', Evaluation::first()))
        ->assertNotFound();
});

it('can update an evaluation', function () {
    $evaluation = Evaluation::factory()->create();
    $data = Evaluation::factory()->for($evaluation->patient)->make();

    actingAs($evaluation->user)->put(route('evaluations.update', $evaluation), $data->toArray())
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('evaluations.show', $evaluation->refresh()));

    expect($evaluation)
        ->title->toBe($data->title)
        ->has_introduction->toBe($data->has_introduction)
        ->introduction->toBe($data->introduction)
        ->hap_patient_form->toBe($data->hap_patient_form)
        ->completed_at->toBeNull();
});

test('updating another user\'s evaluation resolves as a not found request', function () {
    $evaluation = Evaluation::factory()->create();
    $data = Evaluation::factory()->for($patient = Patient::factory()->create())->make();

    actingAs($patient->user)->put(route('evaluations.update', $evaluation), $data->toArray())
        ->assertNotFound();
});

it('can delete an evaluation', function () {
    $evaluation = Evaluation::factory()->create();

    actingAs($evaluation->user)->delete(route('evaluations.destroy', $evaluation))
        ->assertRedirectToRoute('evaluations.index')
        ->assertSessionHas('success', 'Valutazione eliminata con successo.');

    expect(Evaluation::find($evaluation->id))->toBeNull();
});

it('deleting another user\'s evaluation resolves as a not found request', function () {
    $evaluation = Evaluation::factory()->create();

    actingAs(User::factory()->create())->delete(route('evaluations.destroy', $evaluation))
        ->assertNotFound();

    expect(Evaluation::find($evaluation->id))->not->toBeNull();
});
