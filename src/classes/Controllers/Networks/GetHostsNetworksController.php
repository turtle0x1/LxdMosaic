<?php

namespace dhope0000\LXDClient\Controllers\Networks;

use dhope0000\LXDClient\Tools\Networks\GetHostsNetworks;
use Symfony\Component\Routing\Annotation\Route;

class GetHostsNetworksController
{
    public function __construct(
        private readonly GetHostsNetworks $getHostsNetworks
    ) {
    }

    /**
     * @Route("/api/Networks/GetHostsNetworksController/get", name="api_networks_gethostsnetworkscontroller_get", methods={"POST"})
     */
    public function get(int $userId)
    {
        return $this->getHostsNetworks->getAll($userId);
    }
}
