<?php

namespace App\Exceptions;

use App\Http\Controllers\Api\Traits\responseTrait;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    use responseTrait;
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
        $this->reportable(function (Throwable $e , $request) {
            if($request->wantsJson()) {
                return  $this->sendError(500, 'error', $e->getMessage() , 'error');
            }
        });
    }
}
