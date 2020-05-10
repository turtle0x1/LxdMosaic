<?php
namespace dhope0000\LXDClient\Controllers\Images;

use dhope0000\LXDClient\Tools\Images\GetImageProperties;

class GetImagePropertiesController
{
    public function __construct(GetImageProperties $getImageProperties)
    {
        $this->getImageProperties = $getImageProperties;
    }


    public function getAll(int $hostId, string $fingerprint)
    {
        return $this->getImageProperties->getAll($hostId, $fingerprint);
    }
    
    /**
     * This is the list of proprties we support updating for an image
     */
    public function getFiltertedList(int $hostId, string $fingerprint)
    {
        return $this->getImageProperties->getFiltertedList($hostId, $fingerprint);
    }
}
