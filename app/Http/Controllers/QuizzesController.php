<?php

namespace App\Http\Controllers;

use App\Actions\Topic\Section\Quiz\CreateNewQuiz;
use App\Actions\Topic\Section\Quiz\DeleteExistingQuiz;
use App\Actions\Topic\Section\Quiz\UpdateExistingQuiz;
use App\Actions\Topic\Section\Quiz\UploadQuizImage;
use App\Http\Resources\QuizCollection;
use App\Http\Responses\ApiResponse;
use App\Models\Quiz;
use App\Models\Section;
use App\Models\Topic;
use Illuminate\Http\Request;

class QuizzesController extends Controller
{
    public function index(Request $request, Topic $topic, Section $section): QuizCollection
    {
        $query = $section->quizzes();

        $query->when($request->filled('keyword'), fn($query) => $query->where('title', 'like', '%' . $request->input('keyword') . '%'));

        return new QuizCollection($query->paginate($request->input('per_page', 20)));
    }

    public function show(Topic $topic, Section $section, Quiz $quiz): ApiResponse
    {
        return new ApiResponse($quiz);
    }

    public function store(Topic $topic, Section $section): ApiResponse
    {
        $action = new CreateNewQuiz($topic, $section);
        $this->dispatchSync($action);

        return new ApiResponse($action->quiz);
    }

    public function update(Topic $topic, Section $section, Quiz $quiz): ApiResponse
    {
        $action = new UpdateExistingQuiz($quiz);
        $this->dispatchSync($action);

        return new ApiResponse($action->quiz);
    }

    public function upload(Topic $topic, Section $section, Quiz $quiz): ApiResponse
    {
        $action = new UploadQuizImage($quiz);
        $this->dispatchSync($action);

        return new ApiResponse($action->quiz);
    }

    public function destroy(Topic $topic, Section $section, Quiz $quiz): ApiResponse
    {
        $action = new DeleteExistingQuiz($quiz);
        $this->dispatchSync($action);

        return new ApiResponse("Section sucessfully deleted");
    }
}
