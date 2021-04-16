<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PingController extends Controller
{
    public function show(Request $request): Response
    {
        return $this->successResponse($request->all());
    }

    public function store(Request $request): Response
    {
        return response($request->all(), Response::HTTP_CREATED);
    }

    public function update(Request $request): Response
    {
        return $this->successResponse($request->all());
    }

    public function delete(): Response
    {
        return $this->successDeletedResponse();
    }
}
