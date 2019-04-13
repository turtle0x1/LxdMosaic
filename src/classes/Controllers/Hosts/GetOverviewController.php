<?php
namespace dhope0000\LXDClient\Controllers\Hosts;

use dhope0000\LXDClient\Tools\Hosts\GetResources;

class GetOverviewController
{
    public function __construct(GetResources $getResources)
    {
        $this->getResources = $getResources;
    }

    public function get()
    {
        return $this->getResources->getAllHostRecourses();
    }
}
