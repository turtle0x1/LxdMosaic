<?php

namespace dhope0000\LXDClient\Controllers\Search;

use Symfony\Component\Routing\Annotation\Route;
use dhope0000\LXDClient\Tools\Search\SearchIndex;

class SearchIndexController
{
    private $searchIndex;

    public function __construct(SearchIndex $searchIndex)
    {
        $this->searchIndex = $searchIndex;
    }
    /**
     * @Route("/api/search/fuzzy", name="fuzzy search lxd", methods={"POST"})
     */
    public function get($userId, string $search)
    {
       return $this->searchIndex->search($userId, $search);
    }
}
