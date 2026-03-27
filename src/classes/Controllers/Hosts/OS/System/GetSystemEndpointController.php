<?php

namespace dhope0000\LXDClient\Controllers\Hosts\OS\System;

use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Hosts\OS\GetOSOverview;
use Symfony\Component\Routing\Attribute\Route;

class GetSystemEndpointController
{
    public function __construct(
        private readonly GetOSOverview $getOSOverview,
        private readonly FetchUserDetails $fetchUserDetails
    ) {
    }

    #[Route(path: '/api/hosts/os/system/endpoint', name: 'Get OS system data (IncusOS host only)', methods: ['POST', 'GET'])]
    public function get(int $userId, Host $host, string $endpoint)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);
        if (!$isAdmin) {
            throw new \Exception('No access', 1);
        }
        return $host->incusOS->system->$endpoint->info();
    }
}
