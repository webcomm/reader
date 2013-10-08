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

    public function getData()
    {
        if ($this->data === null) {
            $this->loadAdditional();
        }

        return $this->data;
    }

    protected function loadAdditional()
    {
        list($this->caption, $this->data) = $this
            ->carousel
            ->getFinder()
            ->findAdditional($this->file);
    }
}
