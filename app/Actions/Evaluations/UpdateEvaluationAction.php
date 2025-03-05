<?php

declare(strict_types=1);

namespace App\Actions\Evaluations;

use App\Http\Requests\EvaluationRequest;
use App\Models\Evaluation;

final class UpdateEvaluationAction
{
    public function handle(Evaluation $evaluation, EvaluationRequest $request): bool
    {
        $validated = $request->validated();

        unset($validated['patient_id']);

        return $evaluation->update($validated);
    }
}
