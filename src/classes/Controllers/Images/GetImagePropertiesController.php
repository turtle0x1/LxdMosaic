<?php
namespace dhope0000\LXDClient\Controllers\Images;

use dhope0000\LXDClient\Tools\Images\GetImageProperties;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class GetImagePropertiesController
{
    private $getImageProperties;
    
    public function __construct(GetImageProperties $getImageProperties)
    {
        $this->getImageProperties = $getImageProperties;
    }


    /**
     * @Route("/api/Images/GetImagePropertiesController/getAll", name="api_images_getimagepropertiescontroller_getall", methods={"POST"})
     */
    public function getAll(Host $host, string $fingerprint)
    {
        return $this->getImageProperties->getAll($host, $fingerprint);
    }

    /**
     * This is the list of proprties we support updating for an image
      * @Route("/api/Images/GetImagePropertiesController/getFiltertedList", name="api_images_getimagepropertiescontroller_getfiltertedlist", methods={"POST"})
     */
    public function getFiltertedList(Host $host, string $fingerprint)
    {
        return $this->getImageProperties->getFiltertedList($host, $fingerprint);
    }
}
