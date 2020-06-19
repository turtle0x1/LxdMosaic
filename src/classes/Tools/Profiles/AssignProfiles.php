<?php
namespace dhope0000\LXDClient\Tools\Profiles;

use dhope0000\LXDClient\Objects\Host;

class AssignProfiles
{
    public function assign(Host $host, string $instance, array $profiles)
    {
        $info = $host->instances->info($instance);
        $info["profiles"] = array_merge($profiles, $info["profiles"]);

        if (empty($info["devices"])) {
            unset($info["devices"]);
        }

        $host->instances->replace($instance, $info);
    }
}
