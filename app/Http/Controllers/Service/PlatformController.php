<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\CreatePlatformRequest;
use App\Http\Resources\Service\PlatformResource;
use App\Models\Service\Platform;
use App\Models\Wallet;
use App\Services\PlatformHandlerService;
use Illuminate\Http\Request;

class PlatformController extends Controller
{
    public PlatformHandlerService $service;

    public function __construct(PlatformHandlerService $service)
    {

        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * @param CreatePlatformRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreatePlatformRequest $request)
    {
        $recipient_address = $this->service->createNewSubscriber($request->input('platform_level_id'));
        return response()->json(compact('recipient_address'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function platformUsersInfo(Wallet $wallet)
    {
        return PlatformResource::collection($wallet->platforms()->orderBy('id')->get());
    }
}
