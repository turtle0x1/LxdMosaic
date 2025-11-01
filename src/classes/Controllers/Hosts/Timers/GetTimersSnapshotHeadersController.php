<?php

namespace dhope0000\LXDClient\Controllers\Hosts\Timers;

use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Model\Hosts\Timers\FetchTimersSnapshot;
use Symfony\Component\Routing\Annotation\Route;

class GetTimersSnapshotHeadersController
{
    private $fetchUserDetails;
    private $fetchTimersSnapshot;

    public function __construct(
        FetchUserDetails $fetchUserDetails,
        FetchTimersSnapshot $fetchTimersSnapshot
    ) {
        $this->fetchUserDetails = $fetchUserDetails;
        $this->fetchTimersSnapshot = $fetchTimersSnapshot;
    }

    /**
     * @Route("/api/Hosts/Timers/GetTimersSnapshotHeadersController/get", name="api_hosts_timers_gettimerssnapshotheaderscontroller_get", methods={"POST"})
     */
    public function get(int $userId)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);
        if (!$isAdmin) {
            throw new \Exception("No access", 1);
        }
        return $this->fetchTimersSnapshot->fetchLastSevenHeaders();
    }
}
