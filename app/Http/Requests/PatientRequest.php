<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\Gender;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

final class PatientRequest extends FormRequest
{
    /**
     * @return array<string, array<int, string|Enum>>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'max:255'],
            'last_name' => ['required', 'max:255'],
            'gender' => ['nullable', Rule::enum(Gender::class)],
            'birth_date' => ['nullable', 'date'],
            'birth_place' => ['nullable', 'max:255'],
            'address' => ['nullable', 'max:255'],
            'codice_fiscale' => ['nullable', 'codice_fiscale'],
            'therapy_start_date' => [$this->isMethod('put') ? 'required' : 'nullable', 'date'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'max:255'],
            'weight' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'height' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'education' => ['nullable', 'max:255'],
            'job' => ['nullable', 'max:255'],
            'cohabitants' => ['nullable', 'max:255'],
            'drugs' => ['nullable', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'first_name' => 'Nome',
            'last_name' => 'Cognome',
            'gender' => 'Sesso',
            'birth_date' => 'Data di nascita',
            'birth_place' => 'Luogo di nascita',
            'address' => 'Indirizzo',
            'codice_fiscale' => 'Codice Fiscale',
            'therapy_start_date' => 'Data di Inizio Terapia',
            'weight' => 'Peso',
            'height' => 'Altezza',
            'education' => 'Educazione',
            'job' => 'Lavoro',
            'cohabitants' => 'Conviventi',
            'drugs' => 'Farmaci',
        ];
    }
}
