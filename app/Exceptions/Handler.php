<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
//use Symfony\Component\HttpKernel\Exception\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Log;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Validation\ValidationException::class,
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
        //return parent::render($request, $exception);
        //Log::info('url ' . $request->path() . ' exception handler line ' . $e->getLine() . ' and message ' . $e->getMessage());
        //Log::info($request);
        if ($exception instanceof NotFoundHttpException) {
            return $this->pagenotfound($request, $exception);
        } elseif ($exception instanceof MethodNotAllowedHttpException) {
            return $this->methodnotallowed($request, $exception);
        } elseif ($exception instanceof Exception) {
            return $this->exception($request, $exception);
        }
        return parent::render($request, $exception);
    }

    protected function pagenotfound($request, NotFoundHttpException $e)
    {
        //Log::info($request->url());
        //helper::sendEmailException($request->url(), $e->getMessage(), "sjakhetia@flair-solution.com");
        //Log::info($request);
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Page not found.', 'success' => false], 404);
        }
        return response()->view('errors.404', [], 404);
        //return response()->json(['message' => 'Method not found.', 'success' => false], 404);
        //return response('Page not found.', 404);
    }

    //
    protected function methodnotallowed($request, MethodNotAllowedHttpException $e)
    {
        //Log::info($request->url());
        //Log::info($exception);
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Method not found.', 'success' => false], 405);
        }
        return response()->view('errors.405', [], 405);
        //return response()->json(['message' => 'Method not found.', 'success' => false], 405);
        //return response('Method not found.', 405);
    }

    protected function exception($request, Exception $e)
    {
        //$fullRoute = explode('\\', \Route::currentRouteAction());
        
        //    helper::sendEmailException($request, $request->url(), $e->getMessage(), "sjakhetia@flair-solution.com");
        
        //Log::info('ip address ' . @$request->ip());
        //Log::info($fullRoute);
        //Log::info('url ' . $request->url() . ' exception handler line ' . $e->getLine() . ' and message ' . $e->getMessage());
        Log::info($request);
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Please try again.', 'success' => false], 500);
        }
        return response()->view('errors.500', [], 500);
       // return response()->json(['message' => 'Please try again.', 'success' => false], 500);
    }
}
