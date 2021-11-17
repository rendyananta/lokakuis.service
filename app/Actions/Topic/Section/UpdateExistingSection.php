<?php

namespace App\Actions\Topic\Section;

use App\Models\Section;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class UpdateExistingSection
{
    use SerializesModels;

    public Section $section;

    public array $attributes;

    public function __construct(Section $section)
    {
        $request = app(Request::class);
        $this->attributes = $request->validate([
            'name' => 'required|max:255',
            'description' => '',
        ]);

        $this->section = $section;
    }

    public function handle(): bool
    {
        $this->section->fill($this->attributes);
        return $this->section->save();
    }
}