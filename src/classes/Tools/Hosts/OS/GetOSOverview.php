<?php

namespace dhope0000\LXDClient\Tools\Hosts\OS;

use dhope0000\LXDClient\Objects\Host;

class GetOSOverview
{
    public function __construct(
    ) {}

    public function get(Host $host)
    {
        $systemEndpoints = $host->incusOS->system->endpoints();
        $applications = $host->incusOS->applications->all();
        $services = $host->incusOS->services->all();
        return [
            "applications"=>$applications,
            "systemEndpoints"=>$systemEndpoints,
            "services"=>$services
        ];
    }
}
