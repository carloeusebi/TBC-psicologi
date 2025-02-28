<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PriceInterval;
use Database\Factories\PriceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Price extends Model
{
    /** @use HasFactory<PriceFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'stripe_id',
        'interval',
        'amount',
        'label',
    ];

    /**
     * @return BelongsTo<Plan, $this>
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * @return array{interval: 'App\Enums\PriceInterval', amount: 'float'}
     */
    protected function casts(): array
    {
        return [
            'amount' => 'float',
            'interval' => PriceInterval::class,
        ];
    }
}
