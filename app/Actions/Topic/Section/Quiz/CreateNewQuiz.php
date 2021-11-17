<?php

namespace App\Actions\Topic\Section\Quiz;

use App\Models\Quiz;
use App\Models\Section;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class CreateNewQuiz
{
    use SerializesModels;

    public Quiz $quiz;

    public array $attributes;

    public function __construct(Topic $topic, Section $section)
    {
        $request = app(Request::class);
        $this->attributes = $request->validate([
            'question' => 'required|max:255',
            'answer' => 'required',
        ]);

        $this->quiz = new Quiz();
        $this->quiz->section()->associate($section);
        $this->quiz->topic()->associate($topic);
    }

    public function handle(): bool
    {
        $this->quiz->fill($this->attributes);
        return $this->quiz->save();
    }
}