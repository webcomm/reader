<?php

namespace Webcomm\Reader\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class Reader extends Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'reader';
    }
}
