<?php

namespace dhope0000\LXDClient\Controllers\Hosts\OS;

use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Hosts\OS\GetOSOverview;
use Symfony\Component\Routing\Attribute\Route;

class GetHostOSController
{
    public function __construct(
        private readonly GetOSOverview $getOSOverview,
        private readonly FetchUserDetails $fetchUserDetails
    ) {
    }

    #[Route(path: '/api/hosts/os', name: 'Get host OS data (IncusOS host only)', methods: ['POST', 'GET'])]
    public function get(int $userId, Host $host)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);
        if (!$isAdmin) {
            throw new \Exception('No access', 1);
        }
        return $this->getOSOverview->get($host);
    }
}
