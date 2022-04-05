<?php
namespace dhope0000\LXDClient\Controllers\Images;

use dhope0000\LXDClient\Tools\Images\GetImageProperties;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class GetImagePropertiesController
{
    public function __construct(GetImageProperties $getImageProperties)
    {
        $this->getImageProperties = $getImageProperties;
    }
    /**
     * @Route("/api/Images/GetImagePropertiesController/getAll", methods={"POST"}, name="Get all properties of an image", options={"rbac" = "images.read"})
     */
    public function getAll(Host $host, string $fingerprint)
    {
        return $this->getImageProperties->getAll($host, $fingerprint);
    }

    /**
     * @Route("/api/Images/GetImagePropertiesController/getFiltertedList", methods={"POST"}, name="Get list of properties we support updating for an image", options={"rbac" = "images.read"})
     */
    public function getFiltertedList(Host $host, string $fingerprint)
    {
        return $this->getImageProperties->getFiltertedList($host, $fingerprint);
    }
}
