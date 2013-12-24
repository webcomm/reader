<?php

namespace Webcomm\Reader;

use Carbon\Carbon;
use dflydev\markdown\MarkdownExtraParser;

class Story implements ReadableInterface
{
    /**
     * Splitter for excerpt.
     */
    const MORE_SPLITTER = '/~~~/';

    /**
     * The body content of the story.
     *
     * @var string
     */
    protected $body;

    /**
     * The title of the story.
     *
     * @var string
     */
    protected $title;

    /**
     * The URI friendly slug for the story.
     *
     * @var string
     */
    protected $slug;

    /**
     * The date the story was written.
     *
     * @var \Carbon\Carbon
     */
    protected $date;

    /**
     * A collection of story tags.
     * @var array
     */
    protected $tags = array();

    /**
     * Set the story body.
     *
     * @param  string $body
     * @return \Webcomm\Reader\Story
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Get the story body as markdown.
     *
     * @return string
     */
    public function getBody()
    {
        $markdownExtra = new MarkdownExtraParser;
        $html = preg_replace(self::MORE_SPLITTER, '', $this->body);
        return $markdownExtra->transformMarkdown($html);
    }

    /**
     * Get the story body as HTML.
     *
     * @return string
     */
    public function getRawBody()
    {
        return $this->body;
    }

    /**
     * Retrieve the HTML excerpt for the story.
     *
     * @return string
     */
    public function getExcerpt()
    {
        $markdownExtra = new MarkdownExtraParser;
        $excerpt = $this->getRawExcerpt();
        return $markdownExtra->transformMarkdown($excerpt);
    }

    /**
     * Retrieve the plain text excerpt for the story.
     *
     * @return string
     */
    public function getRawExcerpt()
    {
        $parts = preg_split(self::MORE_SPLITTER, $this->body);
        return trim($parts[0]);
    }

    /**
     * Set the story title.
     *
     * @param  string $title
     * @return \Webcomm\Reader\Story
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get the story title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the story slug.
     *
     * @param  string $slug
     * @return \Webcomm\Reader\Story
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * Get the story slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set the date the story was written.
     *
     * @param  string $date
     * @return \Webcomm\Reader\Story
     */
    public function setDate($date)
    {
        $this->date = new Carbon($date);
        return $this;
    }

    /**
     * Get the date the story was written.
     *
     * @return \Carbon\Carbon
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Get the formatted date the story was written.
     *
     * @param  string $format
     * @return string
     */
    public function getFormattedDate($format = 'd/m/y')
    {
        return $this->date->format($format);
    }

    /**
     * Set the story tags.
     *
     * @param  array $tags
     * @return \Webcomm\Reader\Story
     */
    public function setTags(array $tags)
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * Add a tag to the story.
     *
     * @param  string $tag
     * @return \Webcomm\Reader\Story
     */
    public function addTag($tag)
    {
        $this->tags[] = $tag;
        return $this;
    }

    /**
     * Get the story tags.
     *
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * {@inheritDoc}
     */
    public function getReadableSort()
    {
        return $this->date->getTimestamp();
    }
}
