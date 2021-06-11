<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\AnswerStoreRequest;
use App\Http\Requests\Cabinet\AnswerUpdateRequest;
use App\Http\Resources\Cabinet\AnswerResource;
use App\Models\Cabinet\Answer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class AnswerController extends Controller
{
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
        $answer = new Answer();
        $answer->fill($request->validated());
        $answer->save();
        return new AnswerResource($answer);
    }

    /**
     * @param  Answer  $answer
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
        $answer->fill($request->validated());
        $answer->save();
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
