<?php

namespace dhope0000\LXDClient\Controllers\Images;

use dhope0000\LXDClient\Tools\Images\SearchRemoteImages;
use Symfony\Component\Routing\Attribute\Route;

class SearchRemoteImagesController
{
    public function __construct(
        private readonly SearchRemoteImages $getImages
    ) {
    }

    #[Route(path: '/api/Images/SearchRemoteImagesController/get', name: 'api_images_searchremoteimagescontroller_get', methods: ['POST'])]
    public function get($urlKey, $searchType, $searchArch)
    {
        return $this->getImages->get($urlKey, $searchType, $searchArch);
    }
}
