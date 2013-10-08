<?php

namespace Webcomm\Carousel\Facades\Laravel;

use Illuminate\Support\Facades\Facade;

class Carousel extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'carousel';
    }
}
