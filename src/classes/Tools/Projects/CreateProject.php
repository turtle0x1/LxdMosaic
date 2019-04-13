<?php

namespace dhope0000\LXDClient\Tools\Projects;

use dhope0000\LXDClient\Model\Client\LxdClient;

class CreateProject
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function create(
        array $hosts,
        string $name,
        string $description = "",
        array $config = []
    ) {
        $output = [];
        foreach ($hosts as $hostId) {
            $client = $this->lxdClient->getANewClient($hostId);
            //TODO Check if project exsits
            $client->projects->create($name, $description, $config);
        }
        return $output;
    }
}
