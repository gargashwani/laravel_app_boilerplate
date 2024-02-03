<?php

namespace Modules\User\App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     */
    protected string $moduleNamespace = 'Modules\User\App\Http\Controllers';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     */
    public function map(): void
    {
        $this->mapApiRoutes();
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api')
            ->as('api.') // api.users.index, api.users.store, api.users.show, api.users.update, api.users.destroy, etc.->
            ->prefix('api/v1') // api/v1/users, api/v1/users/1, etc.->
            ->middleware('api')
            ->namespace($this->moduleNamespace)
            ->group(module_path('User', '/routes/api.php'));
    }
}
