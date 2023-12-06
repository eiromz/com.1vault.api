<?php

namespace App\Exceptions;

use ErrorException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (MethodNotAllowedException $e, $request) {
            if ($request->is('api/*')) {
                return jsonResponse(ResponseAlias::HTTP_METHOD_NOT_ALLOWED, [
                    'message' => $e->getMessage(),
                ]);
            }
        });
        $this->renderable(function (ModelNotFoundException $e, $request) {
            if ($request->is('api/*')) {
                return jsonResponse(ResponseAlias::HTTP_NOT_FOUND, [
                    'message' => 'Ooops! we could not find what you are looking for',
                ]);
            }
        });
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return jsonResponse(ResponseAlias::HTTP_NOT_FOUND, [
                    'message' => 'Oops! we could not find what you are looking for',
                ]);
            }
        });
        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return jsonResponse(ResponseAlias::HTTP_NETWORK_AUTHENTICATION_REQUIRED, [
                    'message' => $e->getMessage(),
                ]);
            }
        });
        $this->renderable(function (ErrorException $e, $request) {
            if ($request->is('api/*')) {
                return jsonResponse(ResponseAlias::HTTP_PRECONDITION_FAILED, [
                    'message' => $e->getMessage(),
                ]);
            }
        });
        $this->renderable(function(ValidationException $e,$request){
            if ($request->is('api/*')) {
                return jsonResponse(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY, [
                    'message' => $e->getMessage(),
                ]);
            }
        });
        $this->renderable(function (UnprocessableEntityHttpException $e, $request) {
            if ($request->is('api/*')) {
                return jsonResponse(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY, [
                    'message' => $e->getMessage(),
                ]);
            }
        });
    }
}
