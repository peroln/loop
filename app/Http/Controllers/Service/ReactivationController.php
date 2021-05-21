<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\CreatePlatformRequest;
use App\Services\PlatformHandlerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReactivationController extends Controller
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
     * @throws \Throwable
     */
    public function store(CreatePlatformRequest $request)
    {
        if($this->service->reactivationPlatform(Auth::user()->id, $request->input('platform_level_id'))){
            return response()->json('true');
        };
        return response()->json('true', 400);
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
}
