<?php

namespace dhope0000\LXDClient\Controllers\Search;

use dhope0000\LXDClient\Tools\Search\SearchIndex;
use Symfony\Component\Routing\Annotation\Route;

class SearchIndexController
{
    public function __construct(
        private readonly SearchIndex $searchIndex
    ) {
    }

    /**
     * @Route("/api/search/fuzzy", name="fuzzy search lxd", methods={"POST"})
     */
    public function get($userId, string $search)
    {
        return $this->searchIndex->search($userId, $search);
    }
}
