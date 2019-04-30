<?php

namespace dhope0000\LXDClient\Controllers\CloudConfig\Search;

use dhope0000\LXDClient\Model\CloudConfig\Search\SearchCloudConfig;

class SearchController
{
    public function __construct(SearchCloudConfig $searchCloudConfig)
    {
        $this->searchCloudConfig = $searchCloudConfig;
    }

    public function searchAll(string $criteria)
    {
        return $this->searchCloudConfig->searchAll($criteria);
    }
}
