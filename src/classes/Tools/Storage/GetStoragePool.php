<?php

namespace dhope0000\LXDClient\Tools\Storage;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Tools\Utilities\StringTools;
use dhope0000\LXDClient\Model\Hosts\GetDetails;

class GetStoragePool
{
    public function __construct(LxdClient $lxdClient, GetDetails $getDetails)
    {
        $this->lxdClient = $lxdClient;
        $this->getDetails = $getDetails;
    }

    public function get(int $hostId, string $poolName)
    {
        $client = $this->lxdClient->getANewClient($hostId);
        $info = $client->storage->info($poolName);
        $info["resources"] = $client->storage->resources->info($poolName);
        $info["used_by"] = StringTools::usedByStringsToLinks(
            $hostId,
            $info["used_by"],
            $this->getDetails->fetchAlias($hostId)
        );
        return $info;
    }
}
