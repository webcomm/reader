<?php

namespace Webcomm\Carousel\Locations;

use Illuminate\Routing\UrlGenerator;

abstract class BaseGenerator implements GeneratorInterface
{
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
     * Strips the public path out of the given path, so
     * it is safe to be used in URLs. If the public path
     * cannot be extracted, an Exception will be thrown.
     *
     * @param  string  $path
     * @return string
     */
    protected function stripPublicPath($path)
    {
        if (starts_with($realPath = $this->realPath($path), $this->publicPath)) {
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
        do {
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
