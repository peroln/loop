<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\CreatePlatformRequest;
use App\Http\Resources\Service\PlatformLevelResource;
use App\Http\Resources\Service\PlatformResource;
use App\Http\Resources\Service\ReactivationResource;
use App\Models\Service\Platform;
use App\Models\Service\PlatformLevel;
use App\Models\Wallet;
use App\Services\PlatformHandlerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlatformController extends Controller
{
    public PlatformHandlerService $service;

    public function __construct(PlatformHandlerService $service)
    {

        $this->service = $service;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return PlatformResource::collection(Platform::all());
    }

    /**
     * @param  CreatePlatformRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreatePlatformRequest $request)
    {
        $recipient_address = $this->service->createNewSubscriber(Auth::user()->id, $request->input('platform_level_id'));
        return response()->json(compact('recipient_address'));
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param  Wallet  $wallet
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function platformUsersInfo(Wallet $wallet): AnonymousResourceCollection
    {
        return PlatformResource::collection($wallet->platforms()->orderBy('id')->get());
    }

    public function platformReactivationUsersInfo(Wallet $wallet)
    {
        return ReactivationResource::collection($wallet->reactivations);
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getLastCompletePlatforms(): AnonymousResourceCollection
    {
        return PlatformLevelResource::collection(PlatformLevel::all());
    }
}
