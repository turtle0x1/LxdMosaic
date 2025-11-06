<?php

namespace dhope0000\LXDClient\Controllers\Images;

use dhope0000\LXDClient\Tools\Images\GetAllImages;
use Symfony\Component\Routing\Annotation\Route;

class GetImagesController
{
    public function __construct(
        private readonly GetAllImages $getAllImages
    ) {
    }

    /**
     * @Route("/api/Images/GetImagesController/getAllHostImages", name="api_images_getimagescontroller_getallhostimages", methods={"POST"})
     */
    public function getAllHostImages(int $userId)
    {
        return $this->getAllImages->getAllHostImages($userId);
    }
}
