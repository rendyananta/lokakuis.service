<?php

namespace App\Actions\Topic;

use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class UploadTopicBanner 
{
    use SerializesModels;

    public Topic $topic;

    public array $attributes;

    public function __construct(Topic $topic)
    {
        $request = app(Request::class);

        $this->attributes = $request->validate([
            'banner' => 'required|image',
        ]);

        $path = $request->hasFile('banner') ? $request->file('banner')->store('topics') : null;

        $this->attributes['banner'] = $path;

        $this->topic = $topic;
    }

    public function handle(): bool
    {
        $this->topic->fill([
            'banner' => $this->attributes['banner']
        ]);

        return $this->topic->save();
    }

}