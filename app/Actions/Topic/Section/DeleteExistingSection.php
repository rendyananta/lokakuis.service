<?php

namespace App\Actions\Topic\Section;

use App\Models\Section;
use Illuminate\Queue\SerializesModels;

class DeleteExistingSection
{
    use SerializesModels;

    public Section $section;

    public function __construct(Section $section)
    {
        $this->section = $section;
    }

    public function handle(): bool
    {
        return $this->section->delete();
    }
}