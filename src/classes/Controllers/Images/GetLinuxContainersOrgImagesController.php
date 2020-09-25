<?php

namespace dhope0000\LXDClient\Controllers\Images;

use dhope0000\LXDClient\Tools\Images\GetLinuxContainersOrgImages;

class GetLinuxContainersOrgImagesController
{
    public function __construct(GetLinuxContainersOrgImages $getLinuxContainersOrgImages)
    {
        $this->getImages = $getLinuxContainersOrgImages;
    }

    public function get($urlKey, $searchType, $searchArch)
    {
        return $this->getImages->get($urlKey, $searchType, $searchArch);
    }
}
