<?php

namespace App\Actions\Topic\Section\Quiz;

use App\Models\Quiz;
use Illuminate\Queue\SerializesModels;

class DeleteExistingQuiz
{
    use SerializesModels;

    public Quiz $quiz;

    public function __construct(Quiz $quiz)
    {
        $this->quiz = $quiz;
    }

    public function handle(): bool
    {
        return $this->quiz->delete();
    }
}