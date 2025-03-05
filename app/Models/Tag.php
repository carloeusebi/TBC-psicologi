<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

final class Tag extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'color',
    ];

    /**
     * @return MorphToMany<Questionnaire, $this>
     */
    public function questionnaires(): MorphToMany
    {
        return $this->morphedByMany(Questionnaire::class, 'taggable');
    }
}
