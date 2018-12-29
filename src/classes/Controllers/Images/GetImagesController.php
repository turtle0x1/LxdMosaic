<?php
namespace dhope0000\LXDClient\Controllers\Images;

use dhope0000\LXDClient\Model\Images\GetAllImages;

class GetImagesController
{
    public function __construct(GetAllImages $getAllImages)
    {
        $this->getAllImages = $getAllImages;
    }

    public function getAllHostImages()
    {
        return $this->getAllImages->getAllHostImages();
    }
}
