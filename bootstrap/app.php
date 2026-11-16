<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (TokenMismatchException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Your session expired due to inactivity. Please refresh the page and submit again.',
                ], 419);
            }

            if ($request->hasSession()) {
                $request->session()->regenerateToken();
            }

            $targetUrl = url()->previous() ?: (route('home', [], false) . '#consultation');

            return redirect($targetUrl)
                ->withInput($request->except(['_token', 'website_url']))
                ->withErrors([
                    'session' => 'Your page session expired due to inactivity. We refreshed your security token and kept your inputs—please click Submit once more.',
                ]);
        });
    })->create();
