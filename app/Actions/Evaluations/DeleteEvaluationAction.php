<?php

declare(strict_types=1);

namespace App\Actions\Evaluations;

use App\Models\Evaluation;

final class DeleteEvaluationAction
{
    public function handle(Evaluation $evaluation): ?bool
    {
        return $evaluation->delete();
    }
}
