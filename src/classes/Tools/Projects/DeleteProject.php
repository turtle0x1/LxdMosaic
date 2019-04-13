<?php

namespace dhope0000\LXDClient\Tools\Projects;

use dhope0000\LXDClient\Model\Client\LxdClient;

class DeleteProject
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function remove(int $hostId, string $project)
    {
        $client = $this->lxdClient->getANewClient($hostId);
        return $client->projects->remove($project);
    }
}
