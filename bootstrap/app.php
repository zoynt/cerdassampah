<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->validateCsrfTokens(except: [
            'scan',
            'scan/*'
        ]);
        $middleware->alias([
        'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
                $middleware->redirectUsersTo(function (Request $request) {
            // Jika pengguna yang login punya peran 'admin'
            if ($request->user()->hasRole('admin')) {
                // Arahkan ke panel admin
                return '/admin';
            }

            // Jika tidak, arahkan ke dashboard biasa
            return '/dashboard';
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
        $exceptions->renderable(function (NotFoundHttpException $e, Request $request) {
        return redirect()->back()
            ->with('error', 'Data yang Anda cari tidak dapat ditemukan.');
        });

        $exceptions->renderable(function (ModelNotFoundException $e, Request $request) {
        return redirect()->back()
            ->with('error', 'Data yang Anda cari tidak dapat ditemukan.');
        });

        $exceptions->renderable(function (UnauthorizedException $e, Request $request) {
            return redirect()->back()
            ->with('error', 'Anda tidak memiliki izin untuk mengakses halaman tersebut.');
        });

    })->create();
