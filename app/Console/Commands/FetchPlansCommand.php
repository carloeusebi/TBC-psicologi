<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\PriceInterval;
use App\Models\Plan;
use App\Models\Price;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;
use Laravel\Cashier\Cashier;
use Stripe\Price as StripePrice;
use Stripe\Product as StripeProduct;
use Stripe\StripeObject;

final class FetchPlansCommand extends Command
{
    protected $signature = 'plans:fetch';

    protected $description = 'Command description';

    public function handle(): void
    {
        /** @var Collection<int, StripePrice> $prices */
        $prices = collect(Cashier::stripe()->prices->all()->data);
        /** @var Collection<int, StripeProduct> $products */
        $products = collect(Cashier::stripe()->products->all(['active' => true])->data);

        DB::transaction(function () use ($products, $prices): void {
            Price::truncate();
            Plan::truncate();

            $products->each(function (StripeProduct $product) use ($prices): void {
                $plan = Plan::create([
                    'stripe_id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'features' => array_map(fn (StripeObject $feature) => $feature->name, $product->marketing_features), // @phpstan-ignore-line
                    'abilities' => $this->unwrapAbilities($product->metadata->toArray()),
                ]);

                $plan->prices()->createMany(
                    $prices->where('product', $plan->stripe_id)->map(function (StripePrice $price): array {

                        return [
                            'stripe_id' => $price->id,
                            // @phpstan-ignore-next-line
                            'interval' => PriceInterval::from($price->recurring?->interval),
                            'label' => Number::currency((float) $price->unit_amount / 100, $price->currency ?: config('cashier.currency'))
                                .'/'.
                                // @phpstan-ignore-next-line
                                PriceInterval::from($price->recurring?->interval)->translatedLabel(),
                            'amount' => (float) $price->unit_amount / 100,
                        ];
                    })
                );

            });
        });

    }

    /**
     * @param  array<string, numeric-string>  $metadata
     * @return array<string, int>
     */
    private function unwrapAbilities(array $metadata): array
    {
        /** @var array<string, numeric-string> $abilities */
        $abilities = collect($metadata)->undot()->get('abilities');

        return array_map(fn (string $value): int => (int) $value, $abilities);
    }
}
