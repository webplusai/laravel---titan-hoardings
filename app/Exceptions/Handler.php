<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
		if ($request->is('api/*') || $request->ajax()) {
			if ($e instanceof HttpException) {
				$info = null;
				$code = $e->getStatusCode();
			} elseif ($e instanceof \ErrorException) {
				$info = ['error' => env('production') ? 'A server error occurred.' : $e->getMessage()];
				$info['class'] = get_class($e);
				$code = 500;
			} elseif ($e instanceof ValidationException) {
				$info = $e->validator->errors();
				$code = 422;
			} elseif ($e instanceof ServiceValidationException) {
				$info = $e->toArray();
				$code = 422;
			} else {
				$info = [];
				$code = 500;
			}

			return response()->json($info, $code);
		}

		return parent::render($request, $e);
	}

}
