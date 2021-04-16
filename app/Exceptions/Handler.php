<?php

namespace App\Exceptions;

use App\Traits\FormatsErrorResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use App\Exceptions\Model\NotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    use FormatsErrorResponse;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        /* $this->reportable(function (Throwable $exception) {
             if (app()->bound('sentry') && $this->shouldReport($exception)) {
                 app('sentry')->captureException($exception);
             }
         });*/

        $this->renderable(
            function (AccessDeniedHttpException $e, $request) {
                return response(
                    $this->errorResponse(ErrorMessages::ACCESS_DENIED),
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }
        );

        $this->renderable(
            function (NotFoundHttpException $e, $request) {
                return response($this->errorResponse(ErrorMessages::HTTP_NOT_FOUND), Response::HTTP_NOT_FOUND);
            }
        );

        $this->renderable(
            function (\Illuminate\Validation\ValidationException $e, $request) {
                return response($this->errorResponse($e->errors()), Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        );

        $this->renderable(
            function (\Illuminate\Http\Exceptions\ThrottleRequestsException $e, $request) {
                return response($this->errorResponse($e->getMessage()), Response::HTTP_TOO_MANY_REQUESTS);
            }
        );

        $this->renderable(
            function (\Illuminate\Auth\AuthenticationException $e, $request) {
                return response($this->errorResponse(ErrorMessages::UNAUTHENTICATED), Response::HTTP_UNAUTHORIZED);
            }
        );

        $this->renderable(
            function (\Tymon\JWTAuth\Exceptions\JWTException $e, $request) {
                return response($this->errorResponse(ErrorMessages::UNAUTHENTICATED), Response::HTTP_UNAUTHORIZED);
            }
        );

        $this->renderable(
            function (UnauthorizedException $e, $request) {
                return response($this->errorResponse($e->getMessage()), Response::HTTP_UNAUTHORIZED);
            }
        );

        $this->renderable(
            function (\Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException $e, $request) {
                return response($this->errorResponse($e->getMessage()), Response::HTTP_NOT_FOUND);
            }
        );

        $this->renderable(
            function (\Illuminate\Auth\Access\AuthorizationException $e, $request) {
                return response($this->errorResponse(ErrorMessages::UNAUTHORIZED), Response::HTTP_FORBIDDEN);
            }
        );

        $this->renderable(
            function (PostTooLargeException $e, $request) {
                return response($this->errorResponse(ErrorMessages::POST_TOO_LARGE), Response::HTTP_BAD_REQUEST);
            }
        );

        $this->renderable(
            function (NotFoundHttpException $e, $request) {
                return response($this->errorResponse(ErrorMessages::HTTP_NOT_FOUND), Response::HTTP_NOT_FOUND);
            }
        );

        $this->renderable(
            function (ReflectionException $e, $request) {
                return response($this->errorResponse(ErrorMessages::MODEL_LOAD_ERROR), Response::HTTP_FORBIDDEN);
            }
        );

        $this->renderable(
            function (BindingResolutionException $e, $request) {
                logger($e->getTraceAsString());

                return response(
                    $this->errorResponse(ErrorMessages::BINDING_ERROR),
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
            }
        );

        $this->renderable(
            function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, $request) {
                $modelName = Arr::last(explode('\\', $e->getModel()));

                return response($this->errorResponse("${modelName} not found"), Response::HTTP_NOT_FOUND);
            }
        );

        $this->renderable(
            function (\Exception $e, $request) {
                logger($e->getTraceAsString());

                $HTTP_CODES = array_keys(Response::$statusTexts);
                $ERROR_CODE = in_array($e->getCode(), $HTTP_CODES, true) ? $e->getCode() :
                    Response::HTTP_INTERNAL_SERVER_ERROR;

                if (config('app.debug') === true) {
                    return response(
                        array_merge(
                            $this->errorResponse($e->getMessage()),
                            [
                                'trace' => $e->getTraceAsString(),
                            ]
                        ),
                        $ERROR_CODE
                    );
                }

                return response($this->errorResponse($e->getMessage()), $ERROR_CODE);
            }
        );
    }
}
