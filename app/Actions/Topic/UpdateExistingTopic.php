<?php

namespace App\Actions\Topic;

use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class UpdateExistingTopic
{
    use SerializesModels;

    public Topic $topic;

    public array $attributes;

    public function __construct(Topic $topic)
    {
        $request = app(Request::class);
        $this->attributes = $request->validate([
            'name' => 'required|max:255',
            'description' => '',
            'is_public' => 'boolean'
        ]);

        $this->topic = $topic;
    }

    public function handle(): bool
    {
        $this->topic->fill($this->attributes);

        return $this->topic->save();
    }

}
