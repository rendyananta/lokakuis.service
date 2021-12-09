<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'question', 'image', 'answer'
    ];

    protected $appends = [
        'image_url'
    ];

    public function section() 
    {
        return $this->belongsTo(Section::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
   
    public function getImageUrlAttribute(): string
    {   
        return is_null($this->getAttribute('image'))
            ? null 
            : route("api.topic.section.quiz.image", [
                'topic' => $this->topic,
                'section' => $this->section,
                'quiz' => $this
            ]);
    }
}
