<?php

namespace Dmn\CloudflareTurnstile;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * @inheritDoc
     */
    public function boot()
    {
        $this->publishes([
            __DIR__. '/../config/config.php' => config_path('cloudflare'),
        ], 'dmn-cloudflare-config');

        $this->app->bind(
            abstract: Turnstile::class,
            concrete: fn ($app) => new Service($app->config['cloudflare.turnstile']),
        );
    }

    /**
     * @inheritDoc
     */
    public function register()
    {
    }
}
