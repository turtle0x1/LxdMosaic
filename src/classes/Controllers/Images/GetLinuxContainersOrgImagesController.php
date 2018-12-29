<?php

namespace dhope0000\LXDClient\Controllers\Images;

use dhope0000\LXDClient\Model\Images\GetLinuxContainersOrgImages;

class GetLinuxContainersOrgImagesController
{
    public function __construct(GetLinuxContainersOrgImages $getLinuxContainersOrgImages)
    {
        $this->getImages = $getLinuxContainersOrgImages;
    }

    public function get()
    {
        return $this->getImages->get();
    }
}
