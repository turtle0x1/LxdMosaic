<?php
namespace dhope0000\LXDClient\Controllers\Images;

use dhope0000\LXDClient\Tools\Images\GetAllImages;
use Symfony\Component\Routing\Annotation\Route;

class GetImagesController
{
    public function __construct(GetAllImages $getAllImages)
    {
        $this->getAllImages = $getAllImages;
    }
    /**
     * @Route("/api/Images/GetImagesController/getAllHostImages", methods={"POST"}, name="Get all images on all hosts")
     */
    public function getAllHostImages(int $userId)
    {
        return $this->getAllImages->getAllHostImages($userId);
    }
}
