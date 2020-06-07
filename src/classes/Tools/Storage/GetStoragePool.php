<?php

namespace dhope0000\LXDClient\Tools\Storage;

use dhope0000\LXDClient\Tools\Utilities\StringTools;
use dhope0000\LXDClient\Objects\Host;

class GetStoragePool
{
    public function get(Host $host, string $poolName)
    {
        $info = $host->storage->info($poolName);
        $info["resources"] = $host->storage->resources->info($poolName);
        $info["used_by"] = StringTools::usedByStringsToLinks(
            $host->getHostId(),
            $info["used_by"],
            $host->getAlias()
        );
        return $info;
    }
}
