<?php

declare(strict_types=1);

namespace App\Actions\Patient;

use App\Models\Patient;
use Illuminate\Support\Facades\DB;

final class DeletePatientAction
{
    public function handle(Patient $patient): ?bool
    {
        return DB::transaction(function () use ($patient) {
            $patient->evaluations()->delete();

            return $patient->delete();
        });
    }
}
