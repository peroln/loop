<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\BlackListStoreRequest;
use App\Http\Resources\Cabinet\BlackListResource;
use App\Models\Cabinet\BlackList;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BlackListController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(BlackList::class, 'black_list');
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return BlackListResource::collection(BlackList::all());
    }

    /**
     * @param  BlackListStoreRequest  $request
     *
     * @return BlackListResource
     */
    public function store(BlackListStoreRequest $request): BlackListResource
    {
        $black_list = BlackList::create($request->validated());
        return new BlackListResource($black_list);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int                       $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * @param  BlackList  $blackList
     *
     * @return JsonResponse
     */
    public function destroy(BlackList $blackList): JsonResponse
    {
        $blackList->delete();
        return response()->json('The model was deleted successfully');
    }
}
