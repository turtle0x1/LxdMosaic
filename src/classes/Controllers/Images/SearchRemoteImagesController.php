<?php

namespace dhope0000\LXDClient\Controllers\Images;

use dhope0000\LXDClient\Tools\Images\SearchRemoteImages;

class SearchRemoteImagesController
{
    private $getImages;
    
    public function __construct(SearchRemoteImages $searchRemoteImages)
    {
        $this->getImages = $searchRemoteImages;
    }

    public function get($urlKey, $searchType, $searchArch)
    {
        return $this->getImages->get($urlKey, $searchType, $searchArch);
    }
}
