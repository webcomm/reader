<?php

namespace Webcomm\Reader;

use ArrayIterator;
use Closure;
use Countable;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use IteratorAggregate;
use Kurenai\Document;
use Kurenai\DocumentParser;
use Webcomm\Reader\Parsers\HtmlParser;
use Webcomm\Reader\Parsers\TextParser;

class Reader implements Countable, IteratorAggregate
{
    /**
     * The path articles are found.
     *
     * @var string
     */
    protected $basePath;

    /**
     * The story resolver instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $resolver;

    /**
     * The IoC container instance.
     *
     * @var \Illuminate\Container\Container
     */
    protected $container;

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * Array of cached stories.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $stories;

    /**
     * Create a new asset publisher instance.
     *
     * @param  string  $basePath
     * @param  mixed  $resolver
     * @param  \Illuminate\Container\Container  $container
     * @param  \Illuminate\Filesystem\Filesystem  $filesystem
     * @return void
     */
    public function __construct($basePath, $resolver = null, Container $container = null, Filesystem $filesystem = null)
    {
        $this->basePath = $basePath;
        $this->container = $container ?: new Container;
        $this->resolver = $this->parseResolver($resolver);
        $this->filesystem = $filesystem ?: new Filesystem;
    }

    /**
     * Retrieve all readable instances.
     *
     * @return array
     */
    public function all()
    {
        if ($this->stories === null) {
            $documents = $this->loadDocuments();
            $stories = $this->createStories($documents);
            $this->sortStories($stories);
            $this->stories = $stories;
        }

        return $this->stories;
    }

    /**
     * Loads all documents at the given base path.
     *
     * @return array
     */
    protected function loadDocuments()
    {
        $filesystem = $this->filesystem;

        $files = $filesystem->files($this->basePath);
        $files = array_filter($files, function($file) {
            return pathinfo($file, PATHINFO_EXTENSION) === 'md';
        });

        natcasesort($files);

        $parser = new DocumentParser;
        return array_map(function($file) use ($filesystem, $parser) {
            return $parser->parse($filesystem->get($file));
        }, $files);
    }

    /**
     * Resolves documents into readable instances.
     *
     * @param  array  $documents
     * @return array
     */
    protected function createStories(array $documents)
    {
        $stories = array();

        foreach ($documents as $document) {
            $story = call_user_func_array($this->resolver, array($document));
            if ($story) {
                $stories[] = $story;
            }
        }

        return $stories;
    }

    /**
     * Sorts stories by their given sort.
     *
     * @param  array  $stories
     * @return void
     */
    protected function sortStories(array &$stories)
    {
        usort($stories, function(ReadableInterface $a, ReadableInterface $b) {
            $a = $a->getReadableSort();
            $b = $b->getReadableSort();
            if ($a == $b) return 0;
            return ($a > $b) ? -1 : 1;
        });
    }

    /**
     * Parses a resolver, being either a string or Closure.
     *
     * @param  mixed  $resolver
     * @return \Closure
     */
    protected function parseResolver($resolver = null)
    {
        if ($resolver === null) {
            $resolver = 'Webcomm\Reader\Resolvers\StoryResolver@resolve';
        }

        if (is_string($resolver)) {
            $container = $this->container;

            $resolver = function(Document $document) use ($resolver, $container) {
                list($class, $method) = explode('@', $resolver);
                $instance = $container->make($class);
                return $instance->$method($document);
            };
        }

        if ( ! $resolver instanceof Closure) {
            throw new \InvalidArgumentException('Invalid resolver type given.');
        }

        return $resolver;
    }

    /**
     * Get the number of items for the current page.
     *
     * @return int
     */
    public function count()
    {
        return count($this->all());
    }

    /**
     * Get an iterator for the items.
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->all());
    }
}
