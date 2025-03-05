<?php

declare(strict_types=1);

namespace App\Actions\Evaluations;

use App\Http\Requests\EvaluationRequest;
use App\Models\Evaluation;
use App\Models\Patient;

final class CreateEvaluationAction
{
    public function handle(EvaluationRequest $request): Evaluation
    {
        /** @var Patient $patient */
        $patient = Patient::whereBelongsTo($request->user())->findOrFail($request->input('patient_id'));

        $validated = $request->validated();

        unset($validated['patient_id']);

        return $patient->evaluations()->create($validated);
    }
}
