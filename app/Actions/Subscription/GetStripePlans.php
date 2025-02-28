<?php

declare(strict_types=1);

namespace App\Actions\Subscription;

use App\Enums\PriceInterval;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use JsonException;
use Laravel\Cashier\Cashier;
use Number;
use Stripe\Price;
use Stripe\Product;
use Stripe\StripeObject;

final class GetStripePlans
{
    /**
     * @return array<int, mixed>
     */
    public function handle(): array
    {
        try {
            if (! Cashier::stripe()->getApiKey() || App::runningUnitTests()) {
                return $this->mockData();
            }

            // @codeCoverageIgnoreStart
            /** @var Collection<int, Price> $prices */
            $prices = collect(Cashier::stripe()->prices->all()->data);
            /** @var Collection<int, Product> $products */
            $products = collect(Cashier::stripe()->products->all(['active' => true])->data);

            return $products->map(function (Product $product) use ($prices): array {
                $productPrices = $prices->filter(fn ($price): bool => $price->product === $product->id);
                $monthlyPrice = $productPrices->firstWhere('recurring.interval', PriceInterval::Monthly->label());
                $yearlyPrice = $productPrices->firstWhere('recurring.interval', PriceInterval::Yearly->label());

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'features' => json_encode(array_map(fn (StripeObject $feature) => $feature->name, $product->features)), // @phpstan-ignore-line
                    'abilities' => $this->unwrapAbilities($product->metadata->toArray()),
                    'prices' => json_encode([
                        PriceInterval::Monthly->value => Number::currency((float) $monthlyPrice?->unit_amount / 100, $monthlyPrice?->currency ?: config('cashier.currency')).'/mese',
                        PriceInterval::Yearly->value => Number::currency((float) $yearlyPrice?->unit_amount / 100, $yearlyPrice?->currency ?: config('cashier.currency')).'/anno',
                    ]),
                    'pricesId' => json_encode([
                        PriceInterval::Monthly->value => $monthlyPrice?->id,
                        PriceInterval::Yearly->value => $yearlyPrice?->id,
                    ]),
                ];
            })->jsonSerialize();
        } catch (Exception) {
            return [];
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * @return array<int, mixed>
     *
     * @throws JsonException
     */
    private function mockData(): array
    {
        return [
            [
                'id' => 'prod_1',
                'name' => 'Pro',
                'description' => 'The Pro plan',
                'features' => json_encode(['Feature 1', 'Feature 2']),
                'abilities' => $this->unwrapAbilities(['abilities.create-evaluations' => '10']),
                'prices' => json_encode([
                    PriceInterval::Monthly->value => Number::currency(4.99, 'USD').'/mese',
                    PriceInterval::Yearly->value => Number::currency(49.99, 'USD').'/anno',
                ]),
                'pricesId' => json_encode([
                    PriceInterval::Monthly->value => 'price_1',
                    PriceInterval::Yearly->value => 'price_2',
                ]),
            ],
            [
                'id' => 'prod_2',
                'name' => 'Unlimited',
                'description' => 'The Unlimited plan',
                'features' => json_encode(['Feature 1', 'Feature 2', 'Feature 3', 'Feature 4']),
                'abilities' => $this->unwrapAbilities(['abilities.create-evaluations' => '0']),
                'prices' => json_encode([
                    PriceInterval::Monthly->value => Number::currency(9.99, 'USD').'/mese',
                    PriceInterval::Yearly->value => Number::currency(99.99, 'USD').'/anno',
                ]),
                'pricesId' => json_encode([
                    PriceInterval::Monthly->value => 'price_1',
                    PriceInterval::Yearly->value => 'price_2',
                ]),
            ],
        ];
    }

    /**
     * @param  array<string, numeric-string>  $metadata
     *
     * @throws JsonException
     */
    private function unwrapAbilities(array $metadata): string
    {
        /** @var array<int, numeric-string> $abilities */
        $abilities = collect($metadata)->undot()->get('abilities');

        return json_encode(array_map(fn (string $value): int => (int) $value, $abilities), JSON_THROW_ON_ERROR);
    }
}
