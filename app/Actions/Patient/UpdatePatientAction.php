<?php

declare(strict_types=1);

namespace App\Actions\Patient;

use App\Models\Patient;

final class UpdatePatientAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Patient $patient, array $data): bool
    {
        return $patient->update($data);
    }
}
