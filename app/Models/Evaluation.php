<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\EvaluationFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Inertia\Testing\Concerns\Has;

final class Evaluation extends Model
{
    /**
     * @use HasFactory<EvaluationFactory>
     */
    use HasFactory, HasUuids;

    protected $fillable = [
        'title',
        'has_introduction',
        'introduction',
        'hap_patient_form',
        'completed_at',
    ];

    /**
     * @return BelongsTo<Patient, $this>
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * @return HasOneThrough<User, Patient, $this>
     */
    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(
            User::class,
            Patient::class,
            'id',
            'id',
            'patient_id',
            'user_id'
        );
    }

    /**
     * @return array{
     *     has_introduction: 'boolean',
     *     hap_patient_form: 'boolean',
     *     completed_at: 'datetime'
     * }
     */
    protected function casts(): array
    {
        return [
            'has_introduction' => 'boolean',
            'hap_patient_form' => 'boolean',
            'completed_at' => 'datetime',
        ];
    }
}
