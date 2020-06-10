<?php
namespace dhope0000\LXDClient\Tools\Profiles;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Objects\HostsCollection;

class Copy
{
    public function copyToTargetHosts(
        Host $host,
        string $profile,
        HostsCollection $targetHosts,
        string $newName
    ) {
        $profileInfo = $host->profiles->info($profile);

        $profileInfo["devices"] = $profileInfo["devices"] ?: null;
        $profileInfo["config"] = $profileInfo["config"] ?: null;

        // We do this so the copy action is "atomic"
        foreach ($targetHosts as $targetHost) {
            try {
                $x = $targetHost->profiles->info($newName);
                if (!empty($x)) {
                    throw new \Exception("Already have a profile with this name on a target host", 1);
                }
                //TODO Hmm this is probably wrong
            } catch (\Opensaucesystems\Lxd\Exception\NotFoundException $e) {
                // The above code throws an exception when a profile isn't found
                // so we just slienty catch and ignore that exception
            }
        }

        foreach ($targetHosts as $targetHost) {
            $targetHost->profiles->create(
                $newName,
                $profileInfo["description"],
                $profileInfo["config"],
                $profileInfo["devices"]
            );
        }
        return true;
    }
}
