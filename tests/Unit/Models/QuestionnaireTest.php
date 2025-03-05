<?php

declare(strict_types=1);

use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\Tag;

it('has many tags', function () {
    $questionnaire = Questionnaire::whereHas('tags')->first();

    if (! $questionnaire) {
        $this->markTestSkipped('No questionnaire with tags found.');
    }

    expect($questionnaire->tags)->each->toBeInstanceOf(Tag::class);
});

it('has many questions', function () {
    $questionnaire = Questionnaire::whereHas('questions')->first();

    if (! $questionnaire) {
        $this->markTestSkipped('No questionnaire with questions found.');
    }

    expect($questionnaire->questions)->each->toBeInstanceOf(Question::class);
});
