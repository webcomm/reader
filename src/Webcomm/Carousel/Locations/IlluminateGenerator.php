<?php

namespace Webcomm\Carousel\Locations;

use Illuminate\Routing\UrlGenerator;

class IlluminateGenerator extends BaseGenerator implements GeneratorInterface
{
    /**
     * The URL generator which generates
     * the URLs.
     *
     * @var Illuminate\Routing\UrlGenerator
     */
    protected $urlGenerator;

    /**
     * Create a new Illuminate location generator instance.
     *
     * @param  Illuminate\Routing\UrlGenerator  $urlGenerator
     * @param  string  $basePath
     * @param  string  $publicPath
     * @return void
     */
    public function __construct(UrlGenerator $urlGenerator, $basePath, $publicPath)
    {
        $this->urlGenerator = $urlGenerator;
        $this->publicPath = $this->realPath($publicPath);
        $this->basePath = $this->realPath($basePath);

        if (strpos($this->basePath, $this->publicPath) !== 0) {
            throw new \InvalidArgumentException("Base path for carousel files must be within public path.");
        }
    }

    /**
     * Returns the corresponding URL for the
     * given fully qualified path. If a URL
     * cannot be determined a Runtime Exception
     * is thrown.
     *
     * @param  string  $path
     * @return string
     * @throws RuntimeException
     * @todo   Santize does what realpath() does without checking the FS.
     *         maybe clean this up?
     */
    public function getPathUrl($path)
    {
        $path = $this->stripPublicPath($path);

        return $this->urlGenerator->asset($this->removeWindowsSeparator($path));
    }
}
