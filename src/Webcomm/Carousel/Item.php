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

        return $this->caption ?: null;
    }

    public function hasCaption()
    {
        return (bool) $this->getCaption();
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
        $additional = $this
            ->carousel
            ->getFinder()
            ->findAdditional($this->file);

        if ( ! $additional) {
            $this->caption = false;
            $this->data = array();
        }

        list($this->caption, $this->data) = $additional;

        // If the caption returned from the finder as
        // null (the correct data type for no caption)
        // then we should set it to false here, so we
        // don't query the filesystem again because
        // the value is null (the initial state)
        if ($this->caption === null) {
            $this->caption = false;
        }
    }
}
