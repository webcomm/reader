<?php

namespace Webcomm\Reader\Resolvers;

use Illuminate\Support\Str;
use Kurenai\Document;
use Webcomm\Reader\Story;

class StoryResolver implements ResolverInterface
{
    /**
     * {@inheritDoc}
     */
    public function resolve(Document $document)
    {
        $story = new Story;
        $story->setBody($document->getContent());

        foreach (array('title', 'date') as $required) {
            if ( ! $document->get($required)) {
                return false;
            }
            $method = 'set'.ucfirst($required);
            $story->$method($document->get($required));
        }

        if ( ! $slug = $document->get('slug')) {
            $slug = Str::slug($document->get('title'));
        }
        $story->setSlug($slug);

        if ($tags = $document->get('tags')) {
            $tags = array_map('trim', explode(',', $tags));
            $story->setTags($tags);
        }

        return $story;
    }
}
