<?php

namespace Webcomm\Reader\Resolvers;

use Kurenai\Document;

interface ResolverInterface
{
    /**
     * Resolve the given document.
     *
     * @return mixed
     */
    public function resolve(Document $document);
}
