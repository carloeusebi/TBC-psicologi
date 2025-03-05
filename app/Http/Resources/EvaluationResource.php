<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Evaluation
 */
final class EvaluationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'has_introduction' => $this->has_introduction,
            'introduction' => $this->introduction,
            'hap_patient_form' => $this->hap_patient_form,
            'completed_at' => $this->completed_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'patient' => new PatientResource($this->whenLoaded('patient')),
        ];
    }
}
