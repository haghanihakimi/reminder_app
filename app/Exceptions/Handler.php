<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Inertia\Inertia;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Prepare exception for rendering.
     *
     * @param  \Throwable  $e
     * @return \Throwable
     */
    // public function render($request, Throwable $e)
    // {
    //     $response = parent::render($request, $e);

    //     switch ($response->status()) {
    //         case 419:
    //             return back()->with([
    //                 //'message' => __('codes.err419'),
    //                 'message' => __($e->getMessage()),
    //             ]);
    //             break;
    //         case 503:
    //             return back()->with([
    //                 //'message' => __('codes.err503'),
    //                 'message' => __($e->getMessage()),
    //             ]);
    //             break;
    //         case 500:
    //             return back()->with([
    //                 // 'message' => __('codes.err500'),
    //                 'message' => __($e->getMessage()),
    //             ]);
    //             break;
    //         case 404:
    //             return back()->with([
    //                 // 'message' => __('codes.err404'),
    //                 'message' => __($e->getMessage()),
    //             ]);
    //             break;
    //         case 403:
    //             return back()->with([
    //                 // 'message' => __('codes.err403'),
    //                 'message' => __($e->getMessage()),
    //             ]);
    //             break;
    //         default:
    //             return back()->with([
    //                 // 'message' => 'An unknown error occured!',
    //                 'message' => __($e->getMessage()),
    //             ]);
    //             break;
    //     }

    //     return $response;
    // }
}
