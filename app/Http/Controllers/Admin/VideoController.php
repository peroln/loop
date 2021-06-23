<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VideoCreateRequest;
use App\Http\Requests\Admin\VideoUpdateRequest;
use App\Http\Resources\Admin\VideoResource;
use App\Models\Admin\Video;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admins')->except('show');
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return VideoResource::collection(Video::paginate());
    }

    /**
     * @param  VideoCreateRequest  $request
     *
     * @return VideoResource
     */
    public function store(VideoCreateRequest $request): VideoResource
    {
        $video = Video::create($request->validated());
        return new VideoResource($video);
    }

    /**
     * @param  Video  $video
     *
     * @return VideoResource
     */
    public function show(Video $video): VideoResource
    {
        return new VideoResource($video);
    }


    /**
     * @param  VideoUpdateRequest  $request
     * @param  Video               $video
     *
     * @return VideoResource
     */
    public function update(VideoUpdateRequest $request, Video $video): VideoResource
    {
        $video->fill($request->validated());
        $video->save();
        return new VideoResource($video);
    }

    /**
     * @param  Video  $video
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Video $video)
    {
        if ($video->delete()) {
            return response()->json('The model was deleted', 200);
        };
        return response()->json('The model was not deleted', 400);
    }
}
