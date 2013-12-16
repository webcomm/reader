<?php

namespace Webcomm\Carousel;

use Kurenai\DocumentParser;
use SplFileInfo;
use Symfony\Component\Finder\Finder as SymfonyFinder;
use Webcomm\Carousel\Locations\GeneratorInterface as LocationGeneratorInterface;

class Finder
{
    protected $locationGenerator;

    protected $fileTypes = array('jpg', 'jpeg', 'png', 'gif');

    protected $metaTypes = array('md', 'html', 'txt');

    public function __construct(LocationGeneratorInterface $locationGenerator)
    {
        $this->locationGenerator = $locationGenerator;
    }

    public function findFiles($relativePath = null)
    {
        $basePath = $this->locationGenerator->getPath($relativePath);
        $pattern = $this->pattern($this->fileTypes);
        $finder = $this->createSymfonyFinder();

        $finder
            ->files()
            ->in($basePath)
            ->depth('== 0')
            ->name($pattern)
            ->sort(function(\SplFileInfo $a, \SplFileInfo $b) {
                return strnatcmp($a, $b);
            });

        return iterator_to_array($finder);
    }

    public function findMeta($file)
    {
        $parts = pathinfo($file);
        $directory = $parts['dirname'];
        $filename = $parts['filename'];

        $metaTypes = $this->metaTypes;
        $pattern = $this->pattern($metaTypes, $filename);

        $finder = $this->createSymfonyFinder();

        $finder
            ->files()
            ->in($directory)
            ->depth('== 0')
            ->name($pattern)
            ->sort(function(SplFileInfo $a, SplFileInfo $b) use ($metaTypes) {
                $aIndex = array_search($a->getExtension(), $metaTypes);
                $bIndex = array_search($b->getExtension(), $metaTypes);

                if ($aIndex == $bIndex) {
                    return 0;
                }

                return ($aIndex > $bIndex) ? 1 : -1;
            });

        if ($finder->count() === 0) return;

        $files = iterator_to_array($finder);
        $file = reset($files);

        $contents = file_get_contents($file->getRealPath());
        $extension = $file->getExtension();

        $document = $this->createDocumentParser()->parse($contents);

        $caption = '';

        switch ($extension) {
            case 'md':
                $caption = trim($document->getHtmlContent());
                break;

            case 'html':
                $caption = trim($document->getContent());
                break;

            case 'txt':
                $caption = trim(htmlentities($document->getContent()));
                break;
        }

        return array($caption ?: null, $document->get());
    }

    public function getLocationGenerator()
    {
        return $this->locationGenerator;
    }

    public function setLocationGenerator(LocationGeneratorInterface $locationGenerator)
    {
        $this->locationGenerator = $locationGenerator;
    }

    public function getFileTypes()
    {
        return $this->fileTypes;
    }

    public function setFileTypes(array $fileTypes)
    {
        $this->fileTypes = $fileTypes;
    }

    public function getMetaTypes()
    {
        return $this->metaTypes;
    }

    public function setMetaTypes(array $metaTypes)
    {
        $this->metaTypes = array_values($metaTypes);
    }

    protected function pattern($types, $filename = null)
    {
        $pattern = '/';

        if ($filename !== null) {
            $pattern .= preg_quote($filename, '/');
        }

        return $pattern.'\.('.implode('|', $types).')$/';
    }

    protected function createSymfonyFinder()
    {
        return new SymfonyFinder;
    }

    protected function createDocumentParser()
    {
        return new DocumentParser;
    }
}
