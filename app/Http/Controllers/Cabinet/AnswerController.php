<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\AnswerStoreRequest;
use App\Http\Requests\Cabinet\AnswerUpdateRequest;
use App\Http\Resources\Cabinet\AnswerResource;
use App\Models\Cabinet\Answer;
use App\Models\Wallet;
use App\Services\AnswerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AnswerController extends Controller
{


    private AnswerService $answerService;

    public function __construct(AnswerService $answerService)
    {
        $this->answerService = $answerService;
        $this->middleware('auth:wallet')->except(['index', 'show']);
        $this->authorizeResource(Answer::class, 'answer');
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return AnswerResource::collection(Answer::all());
    }

    /**
     * @param  AnswerStoreRequest  $request
     *
     * @return AnswerResource
     */
    public function store(AnswerStoreRequest $request): AnswerResource
    {
        $answer = $this->answerService->storeResource($request);
        return new AnswerResource($answer);
    }

    /**
     * @param  int  $id
     *
     * @return AnswerResource
     */
    public function show(Answer $answer): AnswerResource
    {
        return new AnswerResource($answer);
    }

    /**
     * @param  AnswerUpdateRequest  $request
     * @param  Answer               $answer
     *
     * @return AnswerResource
     */
    public function update(AnswerUpdateRequest $request, Answer $answer): AnswerResource
    {
        $answer = $this->answerService->updateResource($request, $answer);
        return new AnswerResource($answer);
    }

    /**
     * @param  Answer  $answer
     *
     * @return JsonResponse
     */
    public function destroy(Answer $answer): JsonResponse
    {
        $answer->delete();
        return response()->json('The model was deleted successfully');
    }
}
