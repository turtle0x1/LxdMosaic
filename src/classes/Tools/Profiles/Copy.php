<?php
namespace dhope0000\LXDClient\Tools\Profiles;

use dhope0000\LXDClient\Model\Client\LxdClient;

class Copy
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->client = $lxdClient;
    }

    public function copyToTargetHosts(
        int $hostId,
        string $profile,
        array $targetHosts,
        string $newName
    ) {
        $hostClient = $this->client->getANewClient($hostId);
        $profileInfo = $hostClient->profiles->info($profile);

        $profileInfo["devices"] = $profileInfo["devices"] ?: null;
        $profileInfo["config"] = $profileInfo["config"] ?: null;

        $targetHosts = array_unique($targetHosts);
        $targetClientCache = [];
        // We do this so the copy action is "atomic"
        foreach ($targetHosts as $newHostId) {
            $newHostClient = $this->client->getANewClient($newHostId);
            $targetClientCache[] = $newHostClient;
            try {
                $x = $newHostClient->profiles->info($newName);
                if (!empty($x)) {
                    throw new \Exception("Already have a profile with this name on a target host", 1);
                }
            } catch (\Opensaucesystems\Lxd\Exception\NotFoundException $e) {
                // The above code throws an exception when a profile isn't found
                // so we just slienty catch and ignore that exception
            }
        }

        foreach ($targetClientCache as $client) {
            $client->profiles->create(
                $newName,
                $profileInfo["description"],
                $profileInfo["config"],
                $profileInfo["devices"]
            );
        }
        return true;
    }
}
