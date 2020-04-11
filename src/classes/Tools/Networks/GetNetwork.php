<?php

namespace dhope0000\LXDClient\Tools\Networks;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Hosts\GetDetails;
use dhope0000\LXDClient\Tools\Utilities\StringTools;

class GetNetwork
{
    public function __construct(LxdClient $lxdClient, GetDetails $getDetails)
    {
        $this->lxdClient = $lxdClient;
        $this->getDetails = $getDetails;
    }

    public function get(int $hostId, string $network)
    {
        $client = $this->lxdClient->getANewClient($hostId);
        $network = $client->networks->info($network);
        $network["used_by"] = StringTools::usedByStringsToLinks(
            $hostId,
            $network["used_by"],
            $this->getDetails->fetchAlias($hostId)
        );
        return $network;
    }
}
