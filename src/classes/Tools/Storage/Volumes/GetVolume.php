<?php

namespace dhope0000\LXDClient\Tools\Storage\Volumes;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use dhope0000\LXDClient\Tools\Utilities\UsedByFilter;
use dhope0000\LXDClient\Tools\Utilities\StringTools;

class GetVolume
{
    private $validatePermissions;
    private $usedByFilter;
    
    public function __construct(ValidatePermissions $validatePermissions, UsedByFilter $usedByFilter)
    {
        $this->validatePermissions = $validatePermissions;
        $this->usedByFilter = $usedByFilter;
    }

    public function get(int $userId, Host $host, string $pool, string $path, string $project)
    {
        $this->validatePermissions->canAccessHostProjectOrThrow($userId, $host->getHostId(), $project);
        $host->setProject($project);
        $volume = $host->storage->volumes->info($pool, $path);

        $volume["used_by"] = $this->usedByFilter->filterUserProjects($userId, $host, $volume["used_by"]);
        $volume["used_by"] = StringTools::usedByStringsToLinks($host, $volume["used_by"]);

        return $volume;
    }
}
