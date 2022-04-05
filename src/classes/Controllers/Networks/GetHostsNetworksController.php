<?php

namespace dhope0000\LXDClient\Controllers\Networks;

use dhope0000\LXDClient\Tools\Networks\GetHostsNetworks;
use Symfony\Component\Routing\Annotation\Route;

class GetHostsNetworksController
{
    public function __construct(GetHostsNetworks $getHostsNetworks)
    {
        $this->getHostsNetworks = $getHostsNetworks;
    }
    /**
     * @Route("/api/Networks/GetHostsNetworksController/get", methods={"POST"}, name="Get all networks on hosts", options={"rbac" = "networks.read"})
     */
    public function get(int $userId)
    {
        return $this->getHostsNetworks->getAll($userId);
    }
}
