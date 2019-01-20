<?php

namespace dhope0000\LXDClient\Tools\Projects;

use dhope0000\LXDClient\Model\Client\LxdClient;

class DeleteProject
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function remove(string $host, string $project)
    {
        $client = $this->lxdClient->getClientByUrl($host);
        return $client->projects->remove($project);
    }
}
