<?php

namespace dhope0000\LXDClient\Controllers\Hosts;

use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Tools\Hosts\GetHostsOverview;
use Symfony\Component\Routing\Attribute\Route;

class GetHostsController
{
    public function __construct(
        private readonly GetHostsOverview $getHostsOverview,
        private readonly FetchUserDetails $fetchUserDetails
    ) {
    }

    #[Route(path: '/api/Hosts/GetHostsController/getAllHosts', name: 'Get all hosts', methods: ['POST'])]
    public function getAllHosts(int $userId)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);
        if (!$isAdmin) {
            throw new \Exception('No access', 1);
        }
        return $this->getHostsOverview->get();
    }
}
