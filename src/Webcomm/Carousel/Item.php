<?php

namespace Webcomm\Carousel;

class Item
{
    protected $carousel;

    protected $file;

    protected $caption;

    protected $data;

    public function __construct(Carousel $carousel, $file)
    {
        $this->carousel = $carousel;
        $this->file = $file;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getFileInfo($type = null)
    {
        return pathinfo($this->file, $type);
    }

    public function getFileUrl()
    {
        return $this
            ->carousel
            ->getFinder()
            ->getLocationGenerator()
            ->getPathUrl($this->file);
    }

    public function getCaption()
    {
        if ($this->caption === null) {
            $this->loadAdditional();
        }

        return $this->caption;
    }

    public function captionExists() 
    {
        return isset($this->caption);
    }

    public function getData()
    {
        if ($this->data === null) {
            $this->loadAdditional();
        }

        return $this->data;
    }

    public function get($attribute, $default = null)
    {
        if ($this->data === null) {
            $this->loadAdditional();
        }

        if (isset($this->data[$attribute])) {
            return $this->data[$attribute];
        }

        return $default;
    }

    protected function loadAdditional()
    {
        list($this->caption, $this->data) = $this
            ->carousel
            ->getFinder()
            ->findAdditional($this->file);
    }
}
