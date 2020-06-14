<?php

namespace dhope0000\LXDClient\Tools\Projects;

use dhope0000\LXDClient\Objects\HostsCollection;

class CreateProject
{
    public function create(
        HostsCollection $hosts,
        string $name,
        string $description = "",
        array $config = []
    ) {
        foreach ($hosts as $host) {
            //TODO Check if project exsits
            $host->projects->create($name, $description, $config);
        }
        return true;
    }
}
