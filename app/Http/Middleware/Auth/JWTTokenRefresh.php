<?php

namespace App\Http\Middleware\Auth;

use App\Exceptions\Http\BadRequestException;
use App\Exceptions\ErrorMessages;
use App\Exceptions\Http\UnauthorizedException;
use App\Traits\FormatsErrorResponse;
use Closure;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTTokenRefresh extends BaseMiddleware
{
    use FormatsErrorResponse;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     * @throws BadRequestException
     * @throws UnauthorizedException
     */
    public function handle(\Illuminate\Http\Request $request, Closure $next)
    {
        if (!$token = $this->auth->setRequest($request)->getToken()) {
            throw new BadRequestException(ErrorMessages::TOKEN_NOT_PROVIDED);
        }

        try {
            $this->auth->authenticate();
        } catch (TokenExpiredException $e) {
            try {
                $newToken = $this->auth->setRequest($request)->parseToken()->refresh();

                $this->checkPayload($newToken, $user);

                if ($user && $user->active) {
                    $response = $next($request);
                    $response->headers->set('Authorization', $newToken);

                    return $response;
                }

                throw new UnauthorizedException(ErrorMessages::TOKEN_EXPIRED);
            } catch (JWTException $e) {
                throw new UnauthorizedException(ErrorMessages::UNAUTHORIZED);
            }
        }

        return $next($request);
    }

    /**
     * @param string $newToken
     * @param        $user
     *
     */
    protected function checkPayload(string $newToken, &$user): void
    {
        $payload = JWTAuth::setToken($newToken)->getPayload()->toArray();

        if (array_key_exists('for', $payload)) {
            Auth::shouldUse($payload['for']);
        }

        $user = Auth::setToken($newToken)->authenticate();
    }
}
