<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Definisikan aturan password default yang sederhana
        \Illuminate\Validation\Rules\Password::defaults(function () {
            return \Illuminate\Validation\Rules\Password::min(6); // Hanya minimal 6 karakter
        });

        Gate::before(function ($user, string $ability) {
            if (! $user) {
                return false;
            }
            if (isset($user->active) && ! $user->active) {
                return false;
            }

            $role = strtolower(trim((string) ($user->role ?? '')));

            return \App\Support\RoleAbilities::allows($role, $ability);
        });

        // Definisikan rate limiter 'api' yang dibutuhkan oleh throttleApi()
        // di bootstrap/app.php. Tanpa ini, semua API route akan gagal dengan
        // MissingRateLimiterException.
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
