<?php
namespace dhope0000\LXDClient\Controllers\Hosts;

use dhope0000\LXDClient\Tools\Hosts\GetHostOverview;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class GetHostOverviewController
{
    private $getHostOverview;
    
    public function __construct(GetHostOverview $getHostOverview)
    {
        $this->getHostOverview = $getHostOverview;
    }

    /**
     * @Route("/api/Hosts/GetHostOverviewController/get", name="api_hosts_gethostoverviewcontroller_get", methods={"POST"})
     */
    public function get(int $userId, Host $host)
    {
        return $this->getHostOverview->get($userId, $host);
    }
}
