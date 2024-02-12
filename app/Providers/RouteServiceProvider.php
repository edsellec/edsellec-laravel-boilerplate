<?php

namespace App\Providers;

use App\Constants\AppConstant;
use App\Constants\ModuleConstant;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;


class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerApiResource();
        $this->configureRateLimiting();
        $this->mapApiRoutes();
        $this->mapCustomApiRoutes();
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    /**
     * Register API resource routes.
     *
     * @return void
     */
    public function registerApiResource()
    {
        Route::macro('genericApiResource', function ($resource, $controllerClass) {
            $controller = app($controllerClass);
            $routeName = 'api.' . Str::snake(Str::plural(class_basename($controllerClass)));
        
            Route::middleware('api')->group(function () use ($controller, $resource, $routeName) {
                Route::resource($resource, get_class($controller))
                    ->parameters([$resource => 'id'])
                    ->where(['id' => AppConstant::UUID_PATTERN])
                    ->only(['index', 'store', 'show', 'update', 'destroy'])
                    ->names([
                        'index' => $routeName . '.index',
                        'store' => $routeName . '.store',
                        'show' => $routeName . '.show',
                        'update' => $routeName . '.update',
                        'destroy' => $routeName . '.destroy',
                    ]);
            });
        });
        
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    /**
     * Define the custom "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapCustomApiRoutes()
    {
        $modules = ModuleConstant::getModuleRoutes();

        foreach ($modules as $module) {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path("routes/$module/api.php"));
        }
    }
}
