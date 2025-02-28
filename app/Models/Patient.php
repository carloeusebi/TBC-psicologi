<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Gender;
use Carbon\Carbon;
use Database\Factories\PatientFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Patient extends Model
{
    /** @use HasFactory<PatientFactory> */
    use HasFactory, HasUuids;

    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'birth_date',
        'birth_place',
        'address',
        'codice_fiscale',
        'therapy_start_date',
        'email',
        'phone',
        'weight',
        'height',
        'education',
        'job',
        'cohabitants',
        'drugs',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return Attribute<non-falsy-string, never>
     */
    protected function name(): Attribute
    {
        return Attribute::get(
            fn (null $value, mixed $attributes): string => "{$attributes['first_name']} {$attributes['last_name']}"
        );
    }

    /**
     * @return Attribute<int|null, never>
     */
    protected function age(): Attribute
    {
        return Attribute::get(
            fn (null $value, mixed $attributes): ?int => $attributes['birth_date'] ? Carbon::parse($attributes['birth_date'])->age : null,
        );
    }

    /**
     * @return array{
     *     gender: 'App\Enums\Gender',
     *     birth_date: 'date',
     *     therapy_start_date: 'date'
     * }
     */
    protected function casts(): array
    {
        return [
            'gender' => Gender::class,
            'birth_date' => 'date',
            'therapy_start_date' => 'date',
        ];
    }
}
