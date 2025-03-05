<?php

declare(strict_types=1);

namespace App\Concerns;

use App\Enums\Plan as PlanEnum;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Cashier\Billable;
use Laravel\Cashier\Subscription;
use Laravel\Cashier\SubscriptionItem;

/**
 * @property-read Collection<int, Subscription> $subscriptions
 * @property-read Subscription|null $subscription
 */
trait CanSubscribe
{
    use Billable;

    public function hasPlan(PlanEnum $plan): bool
    {
        return $this->plan()->name === $plan;
    }

    public function plan(): Plan
    {
        $stripeProduct = $this->subscription()?->fresh()?->active()
            ? $this->subscription()->items->first()?->stripe_product // @phpstan-ignore-line phpstan can't understand that item is SubscriptionItem
            : null;

        return Plan::where('stripe_id', $stripeProduct)->first() ?? Plan::basic();
    }

    /**
     * @return array<string, int>
     */
    public function getPlanAbilities(): array
    {
        return $this->plan()->abilities ?? [];
    }

    public function canAccessFeature(string $feature, int $userCreated): bool
    {
        $plan = $this->plan();

        $limit = $plan->abilities[$feature] ?? null;

        if ($limit === null) {
            return false;
        }

        if ($limit === 0) {
            return true;
        }

        return $userCreated < $limit;
    }

    protected static function bootCanSubscribe(): void
    {
        static::deleting(function (self $subscriber): void {
            $subscriber->subscriptions->each->cancel();
        });
    }
}
