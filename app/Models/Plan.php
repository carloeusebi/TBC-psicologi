<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PlanFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Laravel\Cashier\Subscription;
use Laravel\Cashier\SubscriptionItem;

final class Plan extends Model
{
    /** @use HasFactory<PlanFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'stripe_id',
        'name',
        'description',
        'features',
        'abilities',
    ];

    protected $with = ['prices'];

    public static function basic(): self
    {
        return self::where('name', 'Basic')->firstOrFail();
    }

    /**
     * @return HasMany<Price, $this>
     */
    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
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
     *     features: 'json',
     *     abilities: 'json',
     * }
     */
    protected function casts(): array
    {
        return [
            'features' => 'json',
            'abilities' => 'json',
        ];
    }
}
