<?php

namespace dhope0000\LXDClient\Controllers\Images;

use dhope0000\LXDClient\Tools\Images\SearchRemoteImages;

class SearchRemoteImagesController
{
    private SearchRemoteImages $getImages;

    public function __construct(SearchRemoteImages $searchRemoteImages)
    {
        $this->getImages = $searchRemoteImages;
    }

    public function get(string $urlKey, string $searchType, string $searchArch) :array
    {
        return $this->getImages->get($urlKey, $searchType, $searchArch);
    }
}
