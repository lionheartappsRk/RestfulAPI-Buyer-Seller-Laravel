<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Dotenv\Exception\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponser;
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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {

        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
            // return $this->errorResponse($exception->getMessage(), 422);
        }

        if ($exception instanceof ModelNotFoundException) {
            $modelName = strtolower(class_basename($exception->getModel()));

            return $this->errorResponse("Does not exists any {$modelName} model", 404);
        }

        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }


        if ($exception instanceof AuthorizationException) {

            return $this->errorResponse($exception->getMessage(), 403);
        }


        if ($exception instanceof NotFoundHttpException) {

            return $this->errorResponse('The specified url not found', 404);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {

            return $this->errorResponse('The specified mehod for the request is invalid', 405);
        }

        if ($exception instanceof HttpException) {

            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        if ($exception instanceof QueryException) {
            //dd($exception);
            $errorCode = $exception->errorInfo[1];

            if ($errorCode == 1451) {
                return $this->errorResponse('Can not remove this resource permentantly. It is related with anu other resource', 409);
            }
        }

        if (config('app.debug')) {
            return parent::render($request, $exception);
        }

        return $this->errorResponse('Unexpected exception. Try later', 500);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->errorResponse('Unauthenticate.', 401);
    }

    /* protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();


        return response()->json($errors, 422);
    } */
}
