<?php
namespace dhope0000\LXDClient\Controllers\Profiles;

use dhope0000\LXDClient\Tools\Profiles\GetProfilesDashboard;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class GetProfilesDashboardController
{
    private $getProfilesDashboard;

    public function __construct(GetProfilesDashboard $getProfilesDashboard)
    {
        $this->getProfilesDashboard = $getProfilesDashboard;
    }
    /**
     * @Route("/api/Profiles/GetProfilesDashboardController/get", methods={"POST"},  name="Get Profile Dashboard")
     */
    public function get(int $userId)
    {
        return $this->getProfilesDashboard->get($userId);
    }
}
