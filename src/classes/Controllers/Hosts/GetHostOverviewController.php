<?php
namespace dhope0000\LXDClient\Controllers\Hosts;

use dhope0000\LXDClient\Tools\Hosts\GetHostOverview;
use dhope0000\LXDClient\Objects\Host;

class GetHostOverviewController
{
    private $getHostOverview;
    
    public function __construct(GetHostOverview $getHostOverview)
    {
        $this->getHostOverview = $getHostOverview;
    }

    public function get(int $userId, Host $host)
    {
        return $this->getHostOverview->get($userId, $host);
    }
}
