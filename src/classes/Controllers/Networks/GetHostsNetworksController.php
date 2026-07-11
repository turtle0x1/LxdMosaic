<?php

namespace dhope0000\LXDClient\Controllers\Networks;

use dhope0000\LXDClient\Tools\Networks\GetHostsNetworks;
use Symfony\Component\Routing\Attribute\Route;

class GetHostsNetworksController
{
    public function __construct(
        private readonly GetHostsNetworks $getHostsNetworks
    ) {
    }

    #[Route(path: '/api/Networks/GetHostsNetworksController/get', name: 'Get hosts networks', methods: ['POST'])]
    public function get(int $userId)
    {
        return $this->getHostsNetworks->getAll($userId);
    }
}
