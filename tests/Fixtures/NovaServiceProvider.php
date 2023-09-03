<?php

namespace NovaSeoEntity\Tests\Fixtures;

use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use NovaSeoEntity\Nova\Resources\SEOInfo;
use NovaSeoEntity\Tests\Fixtures\Nova\Resources\Post;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        SEOInfo::morphToTypes([
            Post::class,
            // ...
        ]);
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return true;
        });
    }


    protected function dashboards()
    {
        return [
        ];
    }

    protected function resources()
    {
        Nova::resources([
            Post::class,
            SEOInfo::class,
        ]);
    }
}
