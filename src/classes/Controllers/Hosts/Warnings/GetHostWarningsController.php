<?php
namespace dhope0000\LXDClient\Controllers\Hosts\Warnings;

use dhope0000\LXDClient\Objects\Host;

class GetHostWarningsController
{
    public function getOnHost(Host $host)
    {
        return $host->warnings->all();
    }
}
