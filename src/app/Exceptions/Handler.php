<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Http\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     * @throws \Exception
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {

        if(env('APP_DEBUG')) {
            return parent::render($request, $e);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            $errorCode = Response::HTTP_METHOD_NOT_ALLOWED;
        } elseif ($e instanceof NotFoundHttpException || $e instanceof ModelNotFoundException) {
            $errorCode = Response::HTTP_NOT_FOUND;
        } elseif ($e instanceof AuthorizationException) {
            $errorCode = Response::HTTP_FORBIDDEN;
        } elseif ($e instanceof ValidationException) {
            return $e->getResponse();
        } else {
            $errorCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json(null, $errorCode);
    }

}
