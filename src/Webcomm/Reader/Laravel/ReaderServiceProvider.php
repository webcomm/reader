<?php

namespace Webcomm\Reader\Laravel;

use Illuminate\Support\ServiceProvider;
use Webcomm\Reader\Factory;

class ReaderServiceProvider extends ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    public function register()
    {
        $this->app['reader'] = $this->app->share(function($app) {
            return new Factory($app, $app['files']);
        });
    }
}
