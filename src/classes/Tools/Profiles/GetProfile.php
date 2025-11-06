<?php

namespace dhope0000\LXDClient\Tools\Profiles;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Utilities\StringTools;
use dhope0000\LXDClient\Tools\Utilities\UsedByFilter;

class GetProfile
{
    public function __construct(
        private readonly UsedByFilter $usedByFilter
    ) {
    }

    public function get(int $userId, Host $host, string $profile)
    {
        $profile = $host->profiles->info($profile);

        $profile['used_by'] = $this->usedByFilter->filterUserProjects($userId, $host, $profile['used_by']);
        $profile['used_by'] = StringTools::usedByStringsToLinks($host, $profile['used_by']);

        return $profile;
    }
}
