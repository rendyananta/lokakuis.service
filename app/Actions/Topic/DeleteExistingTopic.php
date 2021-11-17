<?php

namespace App\Actions\Topic;

use App\Models\Topic;
use Illuminate\Queue\SerializesModels;

class DeleteExistingTopic 
{
    use SerializesModels;

    public Topic $topic;

    public function __construct(Topic $topic)
    {
        $this->topic = $topic;
    }

    public function handle(): bool
    {
        return $this->topic->delete();
    }

}