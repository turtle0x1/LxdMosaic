<?php

namespace dhope0000\LXDClient\Controllers\CloudConfig\Search;

use dhope0000\LXDClient\Model\CloudConfig\Search\SearchCloudConfig;
use Symfony\Component\Routing\Annotation\Route;

class SearchController
{
    public function __construct(SearchCloudConfig $searchCloudConfig)
    {
        $this->searchCloudConfig = $searchCloudConfig;
    }
    /**
     * @Route("/api/CloudConfig/Search/SearchController/searchAll", methods={"POST"}, name="Search cloud config files by name", options={"rbac" = "cloudConfig.read"})
     */
    public function searchAll(string $criteria)
    {
        return $this->searchCloudConfig->searchAll($criteria);
    }
}
