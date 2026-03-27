<?php

namespace dhope0000\LXDClient\Tools\Hosts\OS;

use dhope0000\LXDClient\Objects\Host;

class GetSystemEndpoint
{
    public function __construct(
    ) {}

    public function get(Host $host, string $endpoint)
    {
        return $host->incusOS->system->$endpoint->info();
        
    }
}
