<?php

namespace dhope0000\LXDClient\Tools\Projects;

use dhope0000\LXDClient\Model\Client\LxdClient;

class RenameProject
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function rename(int $hostId, string $project, string $newName)
    {
        $client = $this->lxdClient->getANewClient($hostId);
        return $client->projects->rename($project, $newName);
    }
}
