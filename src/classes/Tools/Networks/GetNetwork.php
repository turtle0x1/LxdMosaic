<?php

namespace dhope0000\LXDClient\Tools\Networks;

use dhope0000\LXDClient\Tools\Utilities\UsedByFilter;
use dhope0000\LXDClient\Tools\Utilities\StringTools;
use dhope0000\LXDClient\Objects\Host;

class GetNetwork
{
    private $usedByFilter;

    public function __construct(UsedByFilter $usedByFilter)
    {
        $this->usedByFilter = $usedByFilter;
    }

    public function get(int $userId, Host $host, string $network)
    {
        $network = $host->networks->info($network);

        if ($network["used_by"] !== null) {
            $network["used_by"] = $this->usedByFilter->filterUserProjects($userId, $host, $network["used_by"]);
            $network["used_by"] = StringTools::usedByStringsToLinks(
                $host,
                $network["used_by"]
            );
        }
        return $network;
    }
}
