<?php
namespace dhope0000\LXDClient\Controllers\Hosts;

use dhope0000\LXDClient\Tools\Hosts\GetHostOverview;

class GetHostOverviewController
{
    public function __construct(GetHostOverview $getHostOverview)
    {
        $this->getHostOverview = $getHostOverview;
    }

    public function get(int $hostId)
    {
        return $this->getHostOverview->get($hostId);
    }
}
