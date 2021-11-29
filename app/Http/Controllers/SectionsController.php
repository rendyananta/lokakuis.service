<?php

namespace App\Http\Controllers;

use App\Actions\Topic\Section\CreateNewSection;
use App\Actions\Topic\Section\DeleteExistingSection;
use App\Actions\Topic\Section\UpdateExistingSection;
use App\Http\Resources\SectionCollection;
use App\Http\Responses\ApiResponse;
use App\Models\Section;
use App\Models\Topic;
use Illuminate\Http\Request;

class SectionsController extends Controller
{
    public function index(Request $request, Topic $topic): SectionCollection
    {
        $query = $topic->sections()->withCount(['quizzes']);

        $query->when($request->filled('keyword'), fn($query) => $query->where('title', 'like', '%' . $request->input('keyword') . '%'));

        return new SectionCollection($query->paginate($request->input('per_page', 20)));
    }

    public function show(Topic $topic, Section $section): ApiResponse
    {
        return new ApiResponse($section);
    }

    public function store(Topic $topic): ApiResponse
    {
        $action = new CreateNewSection($topic);
        $this->dispatchSync($action);

        return new ApiResponse($action->section);
    }

    public function update(Topic $topic, Section $section): ApiResponse
    {
        $action = new UpdateExistingSection($section);
        $this->dispatchSync($action);

        return new ApiResponse($action->section);
    }

    public function destroy(Topic $topic, Section $section): ApiResponse
    {
        $action = new DeleteExistingSection($section);
        $this->dispatchSync($action);

        return new ApiResponse("Section sucessfully deleted");
    }
}
