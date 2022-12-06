<?php

namespace dhope0000\LXDClient\Controllers\CloudConfig\Search;

use dhope0000\LXDClient\Model\CloudConfig\Search\SearchCloudConfig;

class SearchController
{
    private SearchCloudConfig $searchCloudConfig;

    public function __construct(SearchCloudConfig $searchCloudConfig)
    {
        $this->searchCloudConfig = $searchCloudConfig;
    }

    public function searchAll(string $criteria) :array
    {
        return $this->searchCloudConfig->searchAll($criteria);
    }
}
