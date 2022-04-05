<?php
namespace dhope0000\LXDClient\Controllers\Hosts;

use dhope0000\LXDClient\Tools\Hosts\GetHostOverview;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class GetHostOverviewController
{
    public function __construct(GetHostOverview $getHostOverview)
    {
        $this->getHostOverview = $getHostOverview;
    }
    /**
     * @Route("/api/Hosts/GetHostOverviewController/get", methods={"POST"}, name="Get overview data for a host", options={"rbac" = "hosts.read"})
     */
    public function get(int $userId, Host $host)
    {
        return $this->getHostOverview->get($userId, $host);
    }
}
