<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\SubscriptionPriceRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

final class SubscriptionRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * @return array<string, array<int, string|ValidationRule>>
     */
    public function rules(): array
    {
        return [
            'price' => ['required', 'string', new SubscriptionPriceRule],
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
