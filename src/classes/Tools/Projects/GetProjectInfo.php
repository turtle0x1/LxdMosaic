<?php

namespace dhope0000\LXDClient\Tools\Projects;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Tools\Utilities\StringTools;
use dhope0000\LXDClient\Model\Hosts\GetDetails;

class GetProjectInfo
{
    public function __construct(LxdClient $lxdClient, GetDetails $getDetails)
    {
        $this->lxdClient = $lxdClient;
        $this->getDetails = $getDetails;
    }

    public function get(int $hostId, string $project)
    {
        $client = $this->lxdClient->getANewClient($hostId);
        $project = $client->projects->info($project);
        $project["used_by"] = StringTools::usedByStringsToLinks(
            $hostId,
            $project["used_by"],
            $this->getDetails->fetchAlias($hostId),
            $client
        );
        return $project;
    }
}
