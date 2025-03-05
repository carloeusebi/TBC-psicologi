<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Plan as PlanEnum;
use Database\Factories\PlanFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        return self::whereName(PlanEnum::BASIC)->firstOrFail();
    }

    /**
     * @return HasMany<Price, $this>
     */
    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }

    /**
     * @return array{
     *     name: 'App\Enums\Plan',
     *     features: 'json',
     *     abilities: 'json',
     * }
     */
    protected function casts(): array
    {
        return [
            'name' => PlanEnum::class,
            'features' => 'json',
            'abilities' => 'json',
        ];
    }
}
