<?php
namespace dhope0000\LXDClient\Controllers\Images;

use dhope0000\LXDClient\Tools\Images\GetImageProperties;
use dhope0000\LXDClient\Objects\Host;

class GetImagePropertiesController
{
    private GetImageProperties $getImageProperties;

    public function __construct(GetImageProperties $getImageProperties)
    {
        $this->getImageProperties = $getImageProperties;
    }


    public function getAll(Host $host, string $fingerprint) :array
    {
        return $this->getImageProperties->getAll($host, $fingerprint);
    }

    /**
     * This is the list of proprties we support updating for an image
     */
    public function getFiltertedList(Host $host, string $fingerprint) :array
    {
        return $this->getImageProperties->getFiltertedList($host, $fingerprint);
    }
}
