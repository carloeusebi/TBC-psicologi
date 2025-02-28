<?php

declare(strict_types=1);

namespace App\Actions\Patient;

use App\Models\Patient;

final class DeletePatientAction
{
    public function handle(Patient $patient): ?bool
    {
        return $patient->delete();
    }
}
