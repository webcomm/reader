<?php

namespace Webcomm\Reader;

interface ReadableInterface
{
    /**
     * Returns an integer which a readable instance can be sorted.
     *
     * @return int
     */
    public function getReadableSort();
}
