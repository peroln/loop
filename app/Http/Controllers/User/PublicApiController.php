<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class PublicApiController extends Controller
{
    public function address(): Response
    {
        $address = config('tron.contract_address');
        return response(['address' => $address], Response::HTTP_OK);
    }
}
