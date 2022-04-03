<?php

namespace dhope0000\LXDClient\Controllers\Images;

use dhope0000\LXDClient\Tools\Images\SearchRemoteImages;
use Symfony\Component\Routing\Annotation\Route;

class SearchRemoteImagesController
{
    public function __construct(SearchRemoteImages $searchRemoteImages)
    {
        $this->getImages = $searchRemoteImages;
    }
    /**
     * @Route("/api/Images/SearchRemoteImagesController/get", methods={"POST"}, name="Search images on simplestream servers")
     */
    public function get($urlKey, $searchType, $searchArch)
    {
        return $this->getImages->get($urlKey, $searchType, $searchArch);
    }
}
