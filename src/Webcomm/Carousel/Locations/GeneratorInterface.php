<?php

namespace Webcomm\Carousel\Locations;

interface GeneratorInterface
{
    /**
     * Returns the base path for carousel files.
     *
     * @return string
     */
    public function getPath();

    /**
     * Returns the corresponding URL for the
     * given fully qualified path. If a URL
     * cannot be determined a Runtime Exception
     * is thrown.
     *
     * @param  string  $path
     * @return string
     * @throws RuntimeException
     */
    public function getPathUrl($path);

}
