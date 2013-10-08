<?php

namespace Webcomm\Carousel;

use Illuminate\Support\ServiceProvider;
use Webcomm\Carousel\Locations\IlluminateLocationGenerator;

class CarouselServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('webcomm/carousel', 'webcomm/carousel');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerLocationGenerator();
        $this->registerFinder();
        $this->registerCarousel();
    }

    protected function registerLocationGenerator()
    {
        $this->app['carousel.location'] = $this->app->share(function($app) {
            $publicPath = $app['path.public'];
            $basePath = $publicPath.'/'.$app['config']['webcomm/carousel::base_path'];

            return new IlluminateLocationGenerator($app['url'], $basePath, $publicPath);
        });
    }

    protected function registerFinder()
    {
        $this->app['carousel.finder'] = $this->app->share(function($app) {
            return new Finder($app['carousel.location']);
        });
    }

    protected function registerCarousel()
    {
        $this->app['carousel'] = $this->app->share(function($app) {
            return new Carousel($finder);
        });
    }
}
