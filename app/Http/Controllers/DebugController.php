<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\View\View;

/**
 * Class DebugController
 *
 * @package App\Http\Controllers
 */
class DebugController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function wsClient(): View
    {
        return view('ws_client');
    }

    /**
     * @param $file
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getFile($file): Response
    {
        $filePath = "";
        if ($file === "echo") {
            $filePath = base_path("public/sockets_lib/laravel-echo/dist/echo.js");
        } elseif ($file === "io") {
            $filePath = base_path("public/sockets_lib/socket.io-client/dist/socket.io.js");
        }

        if (!file_exists($filePath)) {
            //throw new NotFoundHttpException();
        }

        $content = file_get_contents($filePath);

        return response($content, Response::HTTP_OK, ['Content-Type' => mime_type($filePath)]);
    }
}
