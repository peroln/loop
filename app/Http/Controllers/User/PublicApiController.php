<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class PublicApiController extends Controller
{
    public function address(): Response
    {
        $address = '9sd8fh6sdfh8sd98hsd9fh8dsbfghlsdrgd8r';
        return response(['address' => $address], Response::HTTP_OK);
    }
}
