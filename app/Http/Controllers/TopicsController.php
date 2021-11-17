<?php

namespace App\Http\Controllers;

use App\Actions\Topic\CreateNewTopic;
use App\Actions\Topic\DeleteExistingTopic;
use App\Actions\Topic\UpdateExistingTopic;
use App\Actions\Topic\UploadTopicBanner;
use App\Http\Resources\TopicCollection;
use App\Http\Responses\ApiResponse;
use App\Models\Topic;
use Illuminate\Http\Request;

class TopicsController extends Controller
{
    public function index(Request $request): TopicCollection
    {
        $query = Topic::query()->withCount('sections', 'quizzes')->where('is_public', true);

        $query->when($request->filled('keyword'), fn($query) => $query->where('title', 'like', '%' . $request->input('keyword') . '%'));

        return new TopicCollection($query->paginate($request->input('per_page', 20)));
    }

    public function show(Topic $topic): ApiResponse
    {
        return new ApiResponse($topic);
    }

    public function store(): ApiResponse
    {
        $action = new CreateNewTopic();
        $this->dispatchSync($action);

        return new ApiResponse($action->topic);
    }

    public function update(Topic $topic): ApiResponse
    {
        $action = new UpdateExistingTopic($topic);
        $this->dispatchSync($action);

        return new ApiResponse($action->topic);
    }

    public function upload(Topic $topic): ApiResponse
    {
        $action = new UploadTopicBanner($topic);
        $this->dispatchSync($action);

        return new ApiResponse($action->topic);
    }

    public function destroy(Topic $topic): ApiResponse
    {
        $action = new DeleteExistingTopic($topic);
        $this->dispatchSync($action);

        return new ApiResponse("Topic sucessfully deleted");
    }
}
