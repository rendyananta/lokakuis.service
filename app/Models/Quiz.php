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

    public function section() 
    {
        return $this->belongsTo(Section::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
