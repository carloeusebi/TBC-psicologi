<?php

declare(strict_types=1);
use App\Models\Question;
use App\Models\Questionnaire;

it('belongs to a questionnaire', function () {
    $question = Question::whereHas('questionnaire')->first();

    if (! $question) {
        $this->markTestSkipped('No question with questionnaires found.');
    }

    expect($question->questionnaire)->toBeInstanceOf(Questionnaire::class);
});
