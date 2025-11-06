<?php

namespace dhope0000\LXDClient\Controllers\Images;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Images\GetImageProperties;
use Symfony\Component\Routing\Attribute\Route;

class GetImagePropertiesController
{
    public function __construct(
        private readonly GetImageProperties $getImageProperties
    ) {
    }

    #[Route(path: '/api/Images/GetImagePropertiesController/getAll', name: 'api_images_getimagepropertiescontroller_getall', methods: ['POST'])]
    public function getAll(Host $host, string $fingerprint)
    {
        return $this->getImageProperties->getAll($host, $fingerprint);
    }

    /**
     * This is the list of proprties we support updating for an image
     */
    #[Route(path: '/api/Images/GetImagePropertiesController/getFiltertedList', name: 'api_images_getimagepropertiescontroller_getfiltertedlist', methods: ['POST'])]
    public function getFiltertedList(Host $host, string $fingerprint)
    {
        return $this->getImageProperties->getFiltertedList($host, $fingerprint);
    }
}
