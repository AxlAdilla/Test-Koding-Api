<?php

namespace App\Exceptions;

use Throwable;
use App\Helpers\ResponseJson;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
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
        // return parent::render($request, $exception);
        switch (true) {
            case ($exception instanceof ValidationException):
                $data["message"]=$exception->errors();
                $code=400;
                break;
            case ($exception instanceof AuthenticationException):
                $data["message"]=$exception->getMessage();
                $code=401;
                break;
            default:
                $data["message"]=$exception->getMessage();
                $code=400;
                break;
        }
        return ResponseJson::sendResponse('failed',$data,$code);
    }
}
