<?php
namespace dhope0000\LXDClient\Controllers\Images;

use dhope0000\LXDClient\Tools\Images\GetAllImages;

class GetImagesController
{
    public function __construct(GetAllImages $getAllImages)
    {
        $this->getAllImages = $getAllImages;
    }

    public function getAllHostImages(int $userId)
    {
        return $this->getAllImages->getAllHostImages($userId);
    }
}
