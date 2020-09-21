<?php

namespace dhope0000\LXDClient\Tools\Projects;

use dhope0000\LXDClient\Objects\HostsCollection;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;

class CreateProject
{
    public function __construct(HasExtension $hasExtension)
    {
        $this->hasExtension = $hasExtension;
    }

    public function create(
        HostsCollection $hosts,
        string $name,
        string $description = "",
        array $config = []
    ) {
        foreach ($hosts as $host) {
            //TODO Check if project exsits
            $supportsNetworks = $this->hasExtension->checkWithHost($host, "projects_networks");

            if (!$supportsNetworks) {
                unset($config["features.networks"]);
            }
            $host->projects->create($name, $description, $config);
        }
        return true;
    }
}
