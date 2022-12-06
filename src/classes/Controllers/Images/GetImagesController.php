<?php
namespace dhope0000\LXDClient\Controllers\Images;

use dhope0000\LXDClient\Tools\Images\GetAllImages;

class GetImagesController
{
    private GetAllImages $getAllImages;

    public function __construct(GetAllImages $getAllImages)
    {
        $this->getAllImages = $getAllImages;
    }

    public function getAllHostImages(int $userId) :array
    {
        return $this->getAllImages->getAllHostImages($userId);
    }
}
