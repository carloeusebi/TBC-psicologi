<?php

declare(strict_types=1);
use App\Models\Questionnaire;
use App\Models\Tag;

test('questionnaire belongs to many tags', function () {
    $questionnaire = Questionnaire::firstOrFail();
    $tags = Tag::inRandomOrder()->limit(2)->get();

    $questionnaire->tags()->sync($tags);

    expect($questionnaire->fresh()->tags)->toHaveCount(2)
        ->each->toBeInstanceOf(Tag::class);
});
