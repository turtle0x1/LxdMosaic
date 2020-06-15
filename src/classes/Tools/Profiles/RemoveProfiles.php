<?php
namespace dhope0000\LXDClient\Tools\Profiles;

use dhope0000\LXDClient\Objects\Host;

class RemoveProfiles
{
    public function remove(Host $host, string $instance, array $profilesToRemove)
    {
        $info = $host->instances->info($instance);
        $info["profiles"] = array_values(array_diff($info["profiles"], $profilesToRemove));

        if (empty($info["devices"])) {
            unset($info["devices"]);
        }

        $host->instances->replace($instance, $info);
    }
}
