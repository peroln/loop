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
    public function store(BlackListStoreRequest $request, BlackList $blackList): BlackListResource
    {
        dd('store');
        $blackList->fill($request->validated());
        $blackList->save();
        return new BlackListResource($blackList);
    }

    /**
     * @param  BlackList  $blackList
     */
    public function show(BlackList $blackList)
    {
        //
    }

    /**
     * @param  Request    $request
     * @param  BlackList  $blackList
     */
    public function update(Request $request, BlackList $blackList)
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
