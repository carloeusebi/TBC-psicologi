<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Patient\CreatePatientAction;
use App\Actions\Patient\DeletePatientAction;
use App\Actions\Patient\UpdatePatientAction;
use App\Enums\Gender;
use App\Http\Requests\PatientRequest;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

final class PatientController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): InertiaResponse
    {
        $this->authorize('viewAny', Patient::class);

        $patients = Patient::query()
            ->whereBelongsTo($request->user())
            ->latest()
            ->get();

        return Inertia::render('patients/Index', [
            'patients' => PatientResource::collection($patients),
            'genders' => Gender::options(),
        ]);
    }

    public function store(PatientRequest $request, CreatePatientAction $action): RedirectResponse
    {
        $this->authorize('create', Patient::class);

        $patient = $action->handle($request);

        return to_route('patients.show', $patient);
    }

    public function show(Patient $patient): InertiaResponse
    {
        $this->authorize('view', $patient);

        return Inertia::render('patients/Show', [
            'patient' => new PatientResource($patient),
            'genders' => Gender::options(),
        ]);
    }

    public function update(PatientRequest $request, Patient $patient, UpdatePatientAction $action): RedirectResponse
    {
        $this->authorize('update', $patient);

        $patient = $action->handle($patient, $request);

        return to_route('patients.show', $patient)->with('success', 'Paziente aggiornato.');
    }

    public function destroy(Patient $patient, DeletePatientAction $action): RedirectResponse
    {
        $this->authorize('delete', $patient);

        $action->handle($patient);

        return to_route('patients.index')->with('success', 'Paziente eliminato.');
    }
}
