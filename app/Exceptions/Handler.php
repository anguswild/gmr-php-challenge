<?php

namespace App\Exceptions;

Use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

use App\Traits\ApiResponser;


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
        \League\OAuth2\Server\Exception\OAuthServerException::class,
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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        if($exception instanceof ValidationException){
            return $this->convertValidationExceptionToResponse($exception, $request);
        }
        if($exception instanceof ModelNotFoundException){
            return $this->errorResponse('Model not Found', $request);
        }
        if($exception instanceof AuthorizationException){
            return $this->errorResponse($exception->getMessage(), 403);
        }
        if($exception instanceof AuthenticationException){
            if($this->isFrontEnd($request))
            {
                return redirect()->guest('login');
            }else{
                return $this->errorResponse('Unauthorized', 401);
            }
            
        }
        if($exception instanceof MethodNotAllowedHttpException){
            return $this->errorResponse('Method Not Allowed', 405);
        }      
        if($exception instanceof NotFoundHttpException && $request->wantsJson()){
            return $this->errorResponse('Not Found', 404);
        }else if($exception instanceof NotFoundHttpException){
            return response()->view('errors.' . '404', [], 404);
        }
        
        if($exception instanceof HttpException && $request->wantsJson()){
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }
        if($exception instanceof QueryException){
            $errorCode = $exception->errorInfo[1];
            $errorMessage = $exception->errorInfo[2];

            if ($errorCode == 1062){
                return $this->errorResponse('Duplicate Entry: '.$errorMessage, 409);
            }   
        }
        if($exception instanceof TokenMismatchException){
            return redirect()->back()->withInput($request->input());
        }

        if(config('app.debug') == true){
            return parent::render($request, $exception);
        }else{
            return response()->view('errors.' . '500', [], 500);
        }     
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {

        if($this->isFrontEnd($request))
            {
                return $request->ajax() ? response()->json($e->errors(), 422) : redirect()->back()->withInput($request->input())->withErrors($e->errors());
            }
        return $this->errorResponse(['message' => $e->getMessage(),'errors' => $e->errors()], 422);
    }

    private function isFrontEnd($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }


}
