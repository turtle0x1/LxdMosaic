<?php

namespace dhope0000\LXDClient\Tools\Projects;

use dhope0000\LXDClient\Model\Client\LxdClient;

class RenameProject
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function rename(string $host, string $project, string $newName) {
        $client = $this->lxdClient->getClientByUrl($host);
        return $client->projects->rename($project, $newName);
    }
}
