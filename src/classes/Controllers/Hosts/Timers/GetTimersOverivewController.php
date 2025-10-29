<?php

namespace dhope0000\LXDClient\Controllers\Hosts\Timers;

use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Tools\Hosts\Timers\GetTimersOverview;

class GetTimersOverivewController
{
    private $fetchUserDetails;
    private $getTimersOverview;

    public function __construct(
        FetchUserDetails $fetchUserDetails,
        GetTimersOverview $getTimersOverview
    ) {
        $this->fetchUserDetails = $fetchUserDetails;
        $this->getTimersOverview = $getTimersOverview;
    }

    public function get(int $userId, string $date)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);
        if (!$isAdmin) {
            throw new \Exception("No access", 1);
        }
        $date = new \DateTimeImmutable($date);
        return $this->getTimersOverview->get($date);
    }
}
