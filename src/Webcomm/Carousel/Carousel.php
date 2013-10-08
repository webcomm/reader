<?php

namespace Webcomm\Carousel;

class Carousel
{
    protected $finder;

    protected $items = array();

    public function __construct(Finder $finder)
    {
        $this->finder = $finder;
    }

    public function getItems($relativePath = null)
    {
        $key = $this->getKey($relativePath);

        if ( ! array_key_exists($key, $this->items)) {
            $files = $this->finder->findFiles($relativePath);
            $this->items[$key] = array();

            foreach ($files as $file) {
                $this->items[$key][] = $this->createItem($file);
            }
        }

        return $this->items[$key];
    }

    public function getFinder()
    {
        return $this->finder;
    }

    protected function getKey($relativePath = null)
    {
        if ($relativePath === null) {
            return ':all:';
        }

        return $relativePath;
    }

    protected function createItem($file)
    {
        return new Item($this, $file);
    }
}
