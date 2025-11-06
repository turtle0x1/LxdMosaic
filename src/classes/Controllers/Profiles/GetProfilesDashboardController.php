<?php

namespace dhope0000\LXDClient\Controllers\Profiles;

use dhope0000\LXDClient\Tools\Profiles\GetProfilesDashboard;
use Symfony\Component\Routing\Annotation\Route;

class GetProfilesDashboardController
{
    public function __construct(
        private readonly GetProfilesDashboard $getProfilesDashboard
    ) {
    }

    /**
     * @Route("/api/Profiles/GetProfilesDashboardController/get", methods={"POST"},  name="Get Profile Dashboard")
     */
    public function get(int $userId)
    {
        return $this->getProfilesDashboard->get($userId);
    }
}
