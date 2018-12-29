<?php
namespace dhope0000\LXDClient\Controllers\Networks;

use dhope0000\LXDClient\Model\Networks\GetAllNetworks;

class GetAllNetworksController
{
    public function __construct(GetAllNetworks $getAllNetworks)
    {
        $this->getAllNetworks = $getAllNetworks;
    }

    public function getAll()
    {
        return $this->getAllNetworks->getAll();
    }
}
