<?php

declare(strict_types=1);

namespace App\Actions\Evaluations;

use App\Models\Evaluation;

final class UpdateEvaluationAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Evaluation $evaluation, array $data): bool
    {
        unset($data['patient_id']);

        return $evaluation->update($data);
    }
}
