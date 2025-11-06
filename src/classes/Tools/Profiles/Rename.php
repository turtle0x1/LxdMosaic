<?php

namespace dhope0000\LXDClient\Tools\Profiles;

use dhope0000\LXDClient\Objects\Host;

class Rename
{
    public function rename(Host $host, string $currentName, string $newProfileName)
    {
        return $host->profiles->rename($currentName, $newProfileName);
    }
}
