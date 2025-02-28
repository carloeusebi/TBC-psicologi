<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

final class SubscriptionRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'price' => ['required', 'string', 'exists:prices,stripe_id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'price' => 'prezzo',
        ];
    }

    public function authorize(): bool
    {
        return Auth::check();
    }
}
