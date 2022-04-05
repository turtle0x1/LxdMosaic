<?php

namespace dhope0000\LXDClient\Controllers\Networks;

use dhope0000\LXDClient\Tools\Networks\GetNetwork;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class GetNetworkController
{
    public function __construct(GetNetwork $getNetwork)
    {
        $this->getNetwork = $getNetwork;
    }
    /**
     * @Route("/api/Networks/GetNetworkController/get", methods={"POST"}, name="Get properties of a hosts network", options={"rbac" = "networks.read"})
     */
    public function get(int $userId, Host $host, $network)
    {
        return $this->getNetwork->get($userId, $host, $network);
    }
}
