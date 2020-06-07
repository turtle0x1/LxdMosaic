<?php
namespace dhope0000\LXDClient\Tools\Profiles;

use dhope0000\LXDClient\Tools\Utilities\StringTools;
use dhope0000\LXDClient\Objects\Host;

class GetProfile
{
    public function get(Host $host, string $profile)
    {
        $profile =  $host->profiles->info($profile);

        $profile["used_by"] = StringTools::usedByStringsToLinks(
            $host->getHostId(),
            $profile["used_by"],
            $host->getAlias()
        );

        return $profile;
    }
}
