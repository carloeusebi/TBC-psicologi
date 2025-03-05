<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;

final class EvaluationRequest extends FormRequest
{
    /**
     * @return array<string, list<string|Exists>>
     */
    public function rules(): array
    {
        return [
            'patient_id' => ['required', Rule::exists('patients', 'id')->where('user_id', $this->user()->id)],
            'title' => ['required', 'string', 'max:255'],
            'has_introduction' => ['boolean'],
            'introduction' => ['nullable', 'string'],
            'hap_patient_form' => ['boolean'],
        ];
    }
}
