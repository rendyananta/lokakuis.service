<?php

namespace App\Actions\Topic\Section\Quiz;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class UploadQuizImage 
{
    use SerializesModels;

    public Quiz $quiz;

    public array $attributes;

    public function __construct(Quiz $quiz)
    {
        $request = app(Request::class);

        $this->attributes = $request->validate([
            'image' => 'required|image',
        ]);

        $path = $request->hasFile('image') ? $request->file('image')->store('topics/quiz') : null;

        $this->attributes['image'] = $path;

        $this->quiz = $quiz;
    }

    public function handle(): bool
    {
        $this->quiz->fill([
            'image' => $this->attributes['image']
        ]);

        return $this->quiz->save();
    }

}