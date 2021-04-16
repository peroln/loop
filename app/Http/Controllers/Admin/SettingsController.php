<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\ChangeNameRequest;
use App\Http\Requests\Admin\Settings\ChangePhoneRequest;
use App\Http\Resources\Admin\Auth\AdminDataResource;
use App\Services\AdminService;
use App\Services\Base\BaseAppGuards;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    private AdminService $service;

    /**
     * SettingsController constructor.
     *
     * @param \App\Services\AdminService $service
     */
    public function __construct(AdminService $service)
    {
        $this->service = $service;
        $this->middleware(['auth:admin']);
    }

    /**
     * @param \App\Http\Requests\Admin\Settings\ChangePhoneRequest $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function changePhone(ChangePhoneRequest $request)
    {
        $this->service->changePhone($request->validated()['phone'], Auth::guard(BaseAppGuards::ADMIN)->id());
        $userData = AdminDataResource::make(Auth::guard(BaseAppGuards::ADMIN)->user()->fresh());

        return \response(compact('userData'), Response::HTTP_OK);
    }

    /**
     * @param \App\Http\Requests\Admin\Settings\ChangeNameRequest $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function changeName(ChangeNameRequest $request)
    {
        $this->service->changeName($request->validated()['first_name'], Auth::guard(BaseAppGuards::ADMIN)->id());
        $userData = AdminDataResource::make(Auth::guard(BaseAppGuards::ADMIN)->user()->fresh());

        return \response(compact('userData'), Response::HTTP_OK);
    }
}
