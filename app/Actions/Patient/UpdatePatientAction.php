<?php

declare(strict_types=1);

namespace App\Actions\Patient;

use App\Http\Requests\PatientRequest;
use App\Models\Patient;

final class UpdatePatientAction
{
    public function handle(Patient $patient, PatientRequest $request): Patient
    {
        $patient->update($request->validated());

        return $patient;
    }
}
