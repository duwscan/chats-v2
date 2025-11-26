<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
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
        Scramble::configure()
            ->routes(function (\Illuminate\Routing\Route $route) {
                return str_starts_with($route->uri(), 'api/');
            })
            ->expose(
                ui: '/docs/api',
                document: '/docs/openapi.json',
            );
    }
}
