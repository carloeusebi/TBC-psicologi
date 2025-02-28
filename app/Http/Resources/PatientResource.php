<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Patient */
final class PatientResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'name' => $this->name,
            'gender' => $this->gender?->value,
            'gender_label' => $this->gender?->label(),
            'birth_date' => $this->birth_date,
            'age' => $this->age,
            'birth_place' => $this->birth_place,
            'address' => $this->address,
            'codice_fiscale' => $this->codice_fiscale,
            'therapy_start_date' => $this->therapy_start_date,
            'email' => $this->email,
            'phone' => $this->phone,
            'weight' => $this->weight,
            'height' => $this->height,
            'education' => $this->education,
            'job' => $this->job,
            'cohabitants' => $this->cohabitants,
            'drugs' => $this->drugs,
        ];
    }
}
