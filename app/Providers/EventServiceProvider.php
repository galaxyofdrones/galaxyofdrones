<?php

namespace Koodilab\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected $listen = [];

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        parent::boot();
    }
}
