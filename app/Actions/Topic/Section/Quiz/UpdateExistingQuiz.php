<?php

namespace App\Actions\Topic\Section\Quiz;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class UpdateExistingQuiz
{
    use SerializesModels;

    public Quiz $quiz;

    public array $attributes;

    public function __construct(Quiz $quiz)
    {
        $request = app(Request::class);
        $this->attributes = $request->validate([
            'question' => 'required|max:255',
            'answer' => 'required',
        ]);

        $this->quiz = $quiz;
    }

    public function handle(): bool
    {
        $this->quiz->fill($this->attributes);
        return $this->quiz->save();
    }
}