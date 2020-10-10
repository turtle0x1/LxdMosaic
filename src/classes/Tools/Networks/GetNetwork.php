<?php

namespace dhope0000\LXDClient\Tools\Networks;

use dhope0000\LXDClient\Tools\Utilities\StringTools;
use dhope0000\LXDClient\Objects\Host;

class GetNetwork
{
    public function get(Host $host, string $network)
    {
        $network = $host->networks->info($network);

        if ($network["used_by"] !== null) {
            $network["used_by"] = StringTools::usedByStringsToLinks(
                $host,
                $network["used_by"]
            );
        }
        return $network;
    }
}
