<?php

namespace dhope0000\LXDClient\Controllers\CloudConfig\Search;

use dhope0000\LXDClient\Model\CloudConfig\Search\SearchCloudConfig;
use Symfony\Component\Routing\Annotation\Route;

class SearchController
{
    private $searchCloudConfig;
    
    public function __construct(SearchCloudConfig $searchCloudConfig)
    {
        $this->searchCloudConfig = $searchCloudConfig;
    }

    /**
     * @Route("/api/CloudConfig/Search/SearchController/searchAll", name="api_cloudconfig_search_searchcontroller_searchall", methods={"POST"})
     */
    public function searchAll(string $criteria)
    {
        return $this->searchCloudConfig->searchAll($criteria);
    }
}
