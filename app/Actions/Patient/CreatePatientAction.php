<?php

declare(strict_types=1);

namespace App\Actions\Patient;

use App\Models\Patient;
use App\Models\User;

final class CreatePatientAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(User $user, array $data): Patient
    {
        return $user->patients()->create(
            array_merge(
                $data,
                ['therapy_start_date' => today()],
            )
        );
    }
}
