<?php

declare(strict_types=1);

namespace App\Actions\Subscription;

use Exception;
use Illuminate\Foundation\Auth\User;
use Laravel\Cashier\Checkout;
use Stripe\Exception\ApiErrorException;

final class SubscribeUser
{
    /**
     * @param  \App\Models\User  $user
     *
     * @throws Exception
     * @throws ApiErrorException
     */
    public function handle(User $user, string $price): Checkout
    {
        return $user->newSubscription('default', $price)
            ->checkout([
                'success_url' => route('subscriptions.index'),
                'cancel_url' => route('subscriptions.index'),
                'locale' => 'it',
            ], [
                'preferred_locales' => ['it'],
            ]);
    }
}
