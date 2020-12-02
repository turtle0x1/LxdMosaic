<?php

namespace dhope0000\LXDClient\Tools\Storage;

use dhope0000\LXDClient\Tools\Utilities\StringTools;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Utilities\UsedByFilter;

class GetStoragePool
{
    private $usedByFilter;

    public function __construct(UsedByFilter $usedByFilter)
    {
        $this->usedByFilter = $usedByFilter;
    }

    public function get(int $userId, Host $host, string $poolName)
    {
        $info = $host->storage->info($poolName);

        $info["used_by"] = $this->usedByFilter->filterUserProjects($userId, $host, $info["used_by"]);
        $info["resources"] = $host->storage->resources->info($poolName);
        $info["used_by"] = StringTools::usedByStringsToLinks(
            $host,
            $info["used_by"]
        );

        return $info;
    }
}
