<?php

namespace dhope0000\LXDClient\Tools\Networks;

use dhope0000\LXDClient\Tools\Utilities\StringTools;
use dhope0000\LXDClient\Objects\Host;

class GetNetwork
{
    public function get(Host $host, string $network)
    {
        $network = $host->networks->info($network);

        $network["used_by"] = StringTools::usedByStringsToLinks(
            $host->getHostId(),
            $network["used_by"],
            $host->getAlias()
        );
        
        return $network;
    }
}
