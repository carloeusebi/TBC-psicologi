<?php

declare(strict_types=1);

namespace App\Models;

use App;
use App\Actions\Subscription\GetStripePlans;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Laravel\Cashier\Subscription;
use Laravel\Cashier\SubscriptionItem;
use Sushi\Sushi;

/**
 * @property string $id
 * @property string $name
 * @property string $description
 * @property list<string> $features
 * @property array<string, int> $abilities
 * @property array<string, string> $prices
 * @property array<string, string> $pricesId
 */
final class Plan extends Model
{
    use Sushi;

    public $incrementing = false;

    protected $keyType = 'string';

    public static function basic(): self
    {
        return self::where('name', 'Basic')->firstOrFail();
    }

    /**
     * @return array<int, mixed>
     */
    public function getRows(): array
    {
        $getStripePlans = App::make(GetStripePlans::class);

        return array_merge($getStripePlans->handle());
    }

    /**
     * @return array<string, string>
     */
    public function getSchema(): array
    {
        return [
            'id' => 'string',
            'name' => 'string',
            'description' => 'string',
            'features' => 'json',
            'abilities' => 'json',
            'prices' => 'json',
            'pricesId' => 'json',
        ];
    }

    /**
     * @return HasManyThrough<Subscription, SubscriptionItem, $this>
     */
    public function subscriptions(): HasManyThrough
    {
        return $this->hasManyThrough(
            Subscription::class,
            SubscriptionItem::class,
            'stripe_product',
            'id',
            'id',
            'subscription_id',
        );
    }

    /**
     * @return array{
     *     features: 'array',
     *     abilities: 'array',
     *     prices: 'array',
     *     pricesId: 'array',
     * }
     */
    protected function casts(): array
    {
        return [
            'features' => 'array',
            'abilities' => 'array',
            'prices' => 'array',
            'pricesId' => 'array',
        ];
    }

    protected function sushiShouldCache(): bool
    {
        return true;
    }
}
