<?php

namespace dhope0000\LXDClient\Controllers\Networks;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Networks\GetNetwork;
use Symfony\Component\Routing\Attribute\Route;

class GetNetworkController
{
    public function __construct(
        private readonly GetNetwork $getNetwork
    ) {
    }

    #[Route(path: '/api/Networks/GetNetworkController/get', name: 'api_networks_getnetworkcontroller_get', methods: ['POST'])]
    public function get(int $userId, Host $host, $network)
    {
        return $this->getNetwork->get($userId, $host, $network);
    }
}
