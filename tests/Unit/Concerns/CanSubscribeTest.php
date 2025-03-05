<?php

declare(strict_types=1);

use App\Enums\Plan as PlanEnum;
use App\Enums\PlanFeatures;
use App\Models\Plan;
use App\Models\User;
use Laravel\Cashier\Subscription;
use Laravel\Cashier\SubscriptionItem;

it('returns an user plan', function () {
    $plan = Plan::whereName(PlanEnum::PRO)->firstOrFail();
    $item = SubscriptionItem::factory()->create(['stripe_product' => $plan->stripe_id]);

    expect($item->subscription->user->plan()->id)->toBe($plan->id);
});

it('it returns basic plan if user is not subscribed', function () {
    $user = User::factory()->create();

    expect($user->plan()->id)->toBe(Plan::whereName(PlanEnum::BASIC)->firstOrFail()->id);
});

it('it returns basic plan if the subscription has expired', function () {
    $plan = Plan::whereName(PlanEnum::PRO)->firstOrFail();
    $subscription = Subscription::factory()
        ->has(SubscriptionItem::factory()->state(['stripe_product' => $plan->stripe_id]), 'items')
        ->create();
    $user = $subscription->user;

    expect($user->plan()->id)->toBe($plan->id);

    $subscription->update(['ends_at' => now()->subDay()]);

    expect($subscription->active())->toBeFalse()
        ->and($user->plan()->id)->toBe(Plan::where('name', 'Basic')->firstOrFail()->id);
});

it('checks if the user has a specific plan', function () {
    $plan = Plan::whereName(PlanEnum::PRO)->firstOrFail();
    $item = SubscriptionItem::factory()->create(['stripe_product' => $plan->stripe_id]);

    expect($item->subscription->user->hasPlan(PlanEnum::PRO))->toBeTrue()
        ->and($item->subscription->user->hasPlan(PlanEnum::UNLIMITED))->toBeFalse();
});

it('returns the user\'s plan abilities', function () {
    $plan = Plan::whereName(PlanEnum::PRO)->firstOrFail();
    $item = SubscriptionItem::factory()->create(['stripe_product' => $plan->stripe_id]);

    expect($item->subscription->user->getPlanAbilities())->toBe($plan->abilities);
});

it('checks if the user eligibility for a given feature', function () {
    $plan = Plan::whereName(PlanEnum::PRO)->firstOrFail();
    $item = SubscriptionItem::factory()->create(['stripe_product' => $plan->stripe_id]);

    $feature = PlanFeatures::CREATE_EVALUATIONS->value;
    $feature_value = 10;

    expect(array_keys($plan->abilities)[0])->toBe($feature)
        ->and(array_values($plan->abilities)[0])->toBe($feature_value)
        ->and($item->subscription->user->canAccessFeature($feature, 9))->toBeTrue()
        ->and($item->subscription->user->canAccessFeature($feature, 11))->toBeFalse();
});

it('returns false if the user has no plan', function () {
    $user = User::factory()->create();

    expect($user->canAccessFeature(PlanFeatures::CREATE_EVALUATIONS->value, 10))->toBeFalse();
});

it('returns false if the feature is not defined in the plan', function () {
    $plan = Plan::whereName(PlanEnum::PRO)->firstOrFail();
    $item = SubscriptionItem::factory()->create(['stripe_product' => $plan->id]);

    expect($item->subscription->user->canAccessFeature('unknown_feature', 10))->toBeFalse();
});

test('abilities with value 0 are always accessible', function () {
    $plan = Plan::whereName(PlanEnum::UNLIMITED)->firstOrFail();
    $item = SubscriptionItem::factory()->create(['stripe_product' => $plan->stripe_id]);

    $feature = PlanFeatures::CREATE_EVALUATIONS->value;
    $feature_value = 0;

    expect(array_keys($plan->abilities)[0])->toBe($feature)
        ->and(array_values($plan->abilities)[0])->toBe($feature_value)
        ->and($item->subscription->user->canAccessFeature($feature, 0))->toBeTrue()
        ->and($item->subscription->user->canAccessFeature($feature, PHP_INT_MAX))->toBeTrue();
});
