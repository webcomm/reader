<?php

namespace Webcomm\Reader;

use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;

class Factory
{
    /**
     * The IoC container instance.
     *
     * @var \Illuminate\Container\Container
     */
    protected $container;

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * Create a new asset publisher instance.
     *
     * @param  \Illuminate\Container\Container  $container
     * @param  \Illuminate\Filesystem\Filesystem  $filesystem
     * @return void
     */
    public function __construct(Container $container = null, Filesystem $filesystem = null)
    {
        $this->container = $container ?: new Container;
        $this->filesystem = $filesystem ?: new Filesystem;
    }

    /**
     * Make a new reader for the given base path.
     *
     * @param  mixed  $resolver
     * @param  string  $basePath
     * @return \Webcomm\Reader\Reader
     */
    public function make($basePath, $resolver = null)
    {
        return new Reader($basePath, $resolver, $this->container, $this->filesystem);
    }
}
