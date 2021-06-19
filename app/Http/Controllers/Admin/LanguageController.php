<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\{LanguageCreateRequest, LanguageUpdateRequest};
use App\Http\Resources\Admin\LanguageResource;
use App\Models\Language;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return LanguageResource::collection(Language::all());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param LanguageCreateRequest $request
     * @return LanguageResource
     */
    public function store(LanguageCreateRequest $request): LanguageResource
    {
        $language = Language::create($request->validated());
        return new LanguageResource($language);
    }

    /**
     * @param Language $language
     * @return LanguageResource
     */
    public function show(Language $language): LanguageResource
    {
        return new LanguageResource($language);
    }


    /**
     * Update the specified resource in storage.
     *

     */
    public function update(LanguageUpdateRequest $request, Language $language)
    {
        $language->fill($request->validated());
        $language->save();
        return new LanguageResource($language);
    }

    /**
     * Remove the specified resource from storage.
     * @param Language $language
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Language $language)
    {
        if ($language->delete()) {
            return response()->json('The model was deleted', 200);
        };
        return response()->json('The model was not deleted', 400);
    }
}
