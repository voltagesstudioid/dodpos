<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register custom role middleware alias
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'active' => \App\Http\Middleware\EnsureUserIsActive::class,
            'detect.mobile.sales' => \App\Http\Middleware\DetectMobileSales::class,
        ]);

        $middleware->appendToGroup('web', \App\Http\Middleware\ShareStockMasking::class);
        $middleware->appendToGroup('web', \App\Http\Middleware\LogWebActivity::class);
        $middleware->appendToGroup('web', \App\Http\Middleware\SecurityHeadersMiddleware::class);

        // API rate limiting
        $middleware->throttleApi();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->report(function (AuthorizationException $e) {
            $user = auth()->user();
            $role = strtolower((string) ($user?->role ?? ''));
            $path = (string) request()->path();

            $stockPaths = [
                'gudang/stok',
                'gudang/expired',
                'gudang/minstok',
                'laporan/stok',
            ];

            if ($role !== '' && in_array($role, ['admin3', 'admin4'], true)) {
                foreach ($stockPaths as $p) {
                    if (str_starts_with($path, $p)) {
                        Log::warning('inventory_visibility_authorization_denied', [
                            'user_id' => $user?->id,
                            'role' => $role,
                            'path' => $path,
                            'message' => $e->getMessage(),
                        ]);
                        break;
                    }
                }
            }
        });
    })->create();
