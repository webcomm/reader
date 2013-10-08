<?php

namespace Webcomm\Carousel\Locations;

use Mage;
use Mage_Core_Model_Store;

class MagentoGenerator extends BaseGenerator implements GeneratorInterface
{
    /**
     * Create a new Illuminate location generator instance.
     *
     * @param  Illuminate\Routing\UrlGenerator  $urlGenerator
     * @param  string  $basePath
     * @param  string  $publicPath
     * @return void
     */
    public function __construct($basePath)
    {
        $this->basePath = $this->realPath($basePath);
        $this->publicPath = Mage::getBaseDir();

        if (strpos($this->basePath, Mage::getBaseDir('media')) !== 0) {
            throw new \InvalidArgumentException("Base path for carousel files must be within Magento media path.");
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
     */
    public function getPathUrl($path)
    {
        $path = $this->stripPublicPath($path);

        return Mage::getBaseUrl().$this->removeWindowsSeparator($path);
    }
}
