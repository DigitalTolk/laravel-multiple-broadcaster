<?php

namespace DigitalTolk\MultipleBroadcaster;

use Illuminate\Broadcasting\BroadcastManager;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register the broadcaster
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot()
    {
        $this->app->make(BroadcastManager::class)->extend('multiple', function ($app, array $config) {
            return new MultipleBroadcaster($config, $app->make(BroadcastManager::class));
        });
    }
}
