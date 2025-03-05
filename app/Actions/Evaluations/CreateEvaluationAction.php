<?php

declare(strict_types=1);

namespace App\Actions\Evaluations;

use App\Models\Evaluation;
use App\Models\Patient;
use App\Models\User;

final class CreateEvaluationAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(User $user, array $data): Evaluation
    {
        /** @var Patient $patient */
        $patient = Patient::whereBelongsTo($user)->findOrFail($data['patient_id']);

        unset($data['patient_id']);

        return $patient->evaluations()->create($data);
    }
}
