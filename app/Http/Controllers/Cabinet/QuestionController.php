<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\QuestionStoreRequest;
use App\Http\Requests\Cabinet\QuestionUpdateRequest;
use App\Http\Resources\Cabinet\QuestionResource;
use App\Models\Cabinet\Content;
use App\Models\Cabinet\Question;
use App\Models\Language;
use App\Services\QuestionService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    private QuestionService $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return QuestionResource::collection(Question::all());
    }

    /**
     * @param  QuestionStoreRequest  $request
     *
     * @return QuestionResource
     */
    public function store(QuestionStoreRequest $request): QuestionResource
    {
        $question = $this->questionService->storeResource($request);
        return new QuestionResource($question);

    }

    /**
     * @param  Question  $question
     *
     * @return QuestionResource
     */
    public function show(Question $question): QuestionResource
    {
        return new QuestionResource($question);
    }

    /**
     * @param  QuestionUpdateRequest  $request
     * @param  Question               $question
     *
     * @return QuestionResource
     */
    public function update(QuestionUpdateRequest $request, Question $question): QuestionResource
    {
        $question = $this->questionService->updateResource($request, $question);
        return new QuestionResource($question);
    }

    /**
     * @param  Question  $question
     *
     * @return JsonResponse
     */
    public function destroy(Question $question): JsonResponse
    {
        $question->delete();
        return response()->json('The model was deleted successfully');
    }
}
