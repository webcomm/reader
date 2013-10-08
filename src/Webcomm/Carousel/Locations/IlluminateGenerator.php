<?php

namespace Webcomm\Carousel\Locations;

use Illuminate\Routing\UrlGenerator;

class IlluminateGenerator implements GeneratorInterface
{
    /**
     * The URL generator which generates
     * the URLs.
     *
     * @var Illuminate\Routing\UrlGenerator
     */
    protected $urlGenerator;

    /**
     * The fully qualified base path.
     *
     * @var string
     */
    protected $basePath;

    /**
     * The fully qualified public path.
     *
     * @var string
     */
    protected $publicPath;

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
     * Returns the base path for carousel files.
     *
     * @return string
     */
    public function getPath($relativePath = null)
    {
        if ($relativePath === null) {

            // Already passed through realPath()
            return $this->basePath;
        }

        return $this->realPath($this->basePath.'/'.$relativePath);
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

    /**
     * Strips the public path out of the given path, so
     * it is safe to be used in URLs. If the public path
     * cannot be extracted, an Exception will be thrown.
     *
     * @param  string  $path
     * @return string
     */
    protected function stripPublicPath($path)
    {
        if ( starts_with($realPath = $this->realPath($path), $this->publicPath))
        {
            return ltrim(str_replace($this->publicPath, '', $realPath), '/\\');
        }

        throw new \RuntimeException("Path [$path] must be located in public path [{$this->publicPath}] in order to strip it.");
    }

    /**
     * Returns the real path to the given path.
     *
     * @param  string  $path
     * @return string
     */
    protected function realPath($path)
    {
        $path = $this->sanitizePath($path);

        if ($realpath = realpath($path)) return $realpath;

        return $path;
    }

    /**
     * Sanitizes the path to remove relative references.
     *
     * @param  string  $path
     * @return string  $path
     */
    public function sanitizePath($path)
    {
        $path = str_replace('/./', '/', $path);

        // Strip out any relative paths ("foo/bar/../baz")
        do
        {
            $path = preg_replace('@/[^/]+/\\.\\./@', '/', $path, 1, $changed);
        }
        while ($changed);

        return $path;
    }

    /**
     * Removes windows separators from a path, safe for URLs.
     *
     * @param  string  $path
     * @return string
     */
    protected function removeWindowsSeparator($path)
    {
        return str_replace(DIRECTORY_SEPARATOR, '/', $path);
    }

}
