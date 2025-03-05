<?php

declare(strict_types=1);

use App\Models\Questionnaire;
use App\Models\Tag;

it('belongs to many questionnaires', function () {
    $tag = Tag::whereHas('questionnaires')->first();

    if (! $tag) {
        $this->markTestSkipped('No tag with questionnaires found.');
    }

    expect($tag->questionnaires)->each->toBeInstanceOf(Questionnaire::class);
});
