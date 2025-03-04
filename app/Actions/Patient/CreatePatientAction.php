<?php

declare(strict_types=1);

namespace App\Actions\Patient;

use App\Http\Requests\PatientRequest;
use App\Models\Patient;

final class CreatePatientAction
{
    public function handle(PatientRequest $request): Patient
    {
        return $request->user()->patients()->create(
            array_merge(
                $request->validated(),
                ['therapy_start_date' => today()],
            )
        );
    }
}
