<?php

namespace NovaSeoEntity;

use NovaSeoEntity\Models\SEOInfo;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/nova-seo-entity.php' => config_path('nova-seo-entity.php'),
            ], 'config');


            $this->commands([
                //
            ]);
        }
    }

    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/nova-seo-entity.php', 'nova-seo-entity');

        // seo_image
        $config = $this->app->make('config');

        $config->set('simple-image-manager.drivers', array_merge(
            [ SEOInfo::$thinkSeoImgDriver => $config->get('nova-seo-entity.seo_image', []), ],
            $config->get('simple-image-manager.drivers', [])
        ));

        $this->app->bind('nova-seo-entity', function ($app) {
            return new NovaSeoEntity($app);
        });
    }
}
