<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\QuestionIndexRequest;
use App\Http\Requests\Cabinet\QuestionStoreRequest;
use App\Http\Requests\Cabinet\QuestionUpdateRequest;
use App\Http\Resources\Cabinet\QuestionResource;
use App\Models\Cabinet\Question;
use App\Models\Language;
use App\Services\QuestionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionController extends Controller
{
    private QuestionService $questionService;

    public function __construct(QuestionService $questionService)
    {
        //        $this->authorizeResource(Question::class, 'question');
        $this->middleware('auth:wallet')->except(['index', 'show']);

        $this->questionService = $questionService;
    }

    /**
     * @param  QuestionIndexRequest  $request
     *
     * @return AnonymousResourceCollection
     */
    public function index(QuestionIndexRequest $request): AnonymousResourceCollection
    {
        return QuestionResource::collection(Question::whereHas('contents', function ($q) use ($request) {
            if ($request->has('language')) {
                $language_id = Language::where('shortcode', $request->input('language'))->firstOrFail()->id;
                $q->where('language_id', $language_id);
            }
            if ($request->has('search')) {
                $q->where('text', 'ILIKE', '%' . $request->input('search') . '%');
            }

        })
            ->paginate($request->input('count', 15)));
    }

    /**
     * @param  QuestionStoreRequest  $request
     *
     * @return QuestionResource
     */
    public function store(QuestionStoreRequest $request): QuestionResource
    {
        $this->authorize('create', Question::class);
        $question = $this->questionService->storeResource($request);
        return new QuestionResource($question);

    }

    /**
     * @param  int  $id
     *
     * @return QuestionResource
     */
    public function show(int $id): QuestionResource
    {
        return new QuestionResource(Question::findOrFail($id));
    }

    /**
     * @param  QuestionUpdateRequest  $request
     * @param  Question               $question
     *
     * @return QuestionResource
     */
    public function update(QuestionUpdateRequest $request, Question $question): QuestionResource
    {
        $this->authorize('update', $question);
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
        $this->authorize('delete', $question);
        $question->delete();
        return response()->json('The model was deleted successfully');
    }
}
