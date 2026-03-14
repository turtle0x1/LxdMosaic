<?php

namespace dhope0000\LXDClient\Controllers\Hosts\OS\Applications;

use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Hosts\OS\GetOSOverview;
use Symfony\Component\Routing\Attribute\Route;

class GetApplicationController
{
    public function __construct(
        private readonly GetOSOverview $getOSOverview,
        private readonly FetchUserDetails $fetchUserDetails
    ) {
    }

    #[Route(path: '/api/hosts/os/applications', name: 'Get OS system application (IncusOS host only)', methods: ['POST', 'GET'])]
    public function get(int $userId, Host $host, string $application)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);
        if (!$isAdmin) {
            throw new \Exception('No access', 1);
        }
        return $host->incusOS->applications->info($application);
    }
}
