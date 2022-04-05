<?php
namespace dhope0000\LXDClient\Controllers\Hosts\Warnings;

use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class GetHostWarningsController
{
    /**
     * @Route("/api/Hosts/Warnings/GetHostWarningsController/getOnHost", methods={"POST"}, name="Get all warnings on a host", options={"rbac" = "hosts.warnings.read"})
     */
    public function getOnHost(Host $host)
    {
        return $host->warnings->all();
    }
}
