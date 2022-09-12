<?php

namespace Sidis405\LaravelDynamicServersDigitalOcean\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Sidis405\LaravelDynamicServersDigitalOcean\Listeners\UpdateServerMeta;
use Spatie\DynamicServers\Events\ServerRunningEvent;

class DigitalOceanEventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ServerRunningEvent::class => [
            UpdateServerMeta::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
