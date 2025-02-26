<?php

declare(strict_types=1);

namespace App\Rules;

use App\Models\Plan;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class SubscriptionPriceRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $availablePrices = array_filter(Plan::all()->pluck('pricesId')->flatten()->toArray());

        if (! in_array($value, $availablePrices, true)) {
            $fail('Il :attribute selezionato non è valido.');
        }
    }
}
