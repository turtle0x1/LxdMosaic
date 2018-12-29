<?php
namespace dhope0000\LXDClient\Controllers\Networks;

use dhope0000\LXDClient\Model\Networks\GetNetwork;

class GetNetworkController
{
    public function __construct(GetNetwork $getNetwork)
    {
        $this->getNetwork = $getNetwork;
    }

    public function getNetworkByName(string $host, string $networkName)
    {
        return $this->getNetwork->getByName($host, $networkName);
    }
}
