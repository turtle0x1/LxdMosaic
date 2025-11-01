<?php

namespace dhope0000\LXDClient\Controllers\Networks;

use dhope0000\LXDClient\Tools\Networks\GetNetwork;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class GetNetworkController
{
    private $getNetwork;
    
    public function __construct(GetNetwork $getNetwork)
    {
        $this->getNetwork = $getNetwork;
    }

    /**
     * @Route("/api/Networks/GetNetworkController/get", name="api_networks_getnetworkcontroller_get", methods={"POST"})
     */
    public function get(int $userId, Host $host, $network)
    {
        return $this->getNetwork->get($userId, $host, $network);
    }
}
