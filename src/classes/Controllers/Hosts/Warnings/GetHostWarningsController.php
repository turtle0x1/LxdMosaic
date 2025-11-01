<?php
namespace dhope0000\LXDClient\Controllers\Hosts\Warnings;

use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class GetHostWarningsController
{
    /**
     * @Route("/api/Hosts/Warnings/GetHostWarningsController/getOnHost", name="api_hosts_warnings_gethostwarningscontroller_getonhost", methods={"POST"})
     */
    public function getOnHost(Host $host)
    {
        return $host->warnings->all();
    }
}
