<?php

namespace dhope0000\LXDClient\Controllers\Hosts\Timers;

use dhope0000\LXDClient\Model\Hosts\Timers\FetchTimersSnapshot;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use Symfony\Component\Routing\Attribute\Route;

class GetTimersSnapshotHeadersController
{
    public function __construct(
        private readonly FetchUserDetails $fetchUserDetails,
        private readonly FetchTimersSnapshot $fetchTimersSnapshot
    ) {
    }

    #[Route(path: '/api/Hosts/Timers/GetTimersSnapshotHeadersController/get', name: 'api_hosts_timers_gettimerssnapshotheaderscontroller_get', methods: ['POST'])]
    public function get(int $userId)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);
        if (!$isAdmin) {
            throw new \Exception('No access', 1);
        }
        return $this->fetchTimersSnapshot->fetchLastSevenHeaders();
    }
}
