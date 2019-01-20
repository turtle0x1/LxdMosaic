<?php

namespace dhope0000\LXDClient\Tools\Projects;

use dhope0000\LXDClient\Model\Client\LxdClient;

class GetProjectInfo
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function get(string $host, string $project) {
        $client = $this->lxdClient->getClientByUrl($host);
        return $client->projects->info($project);
    }
}
