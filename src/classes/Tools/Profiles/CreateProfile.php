<?php
namespace dhope0000\LXDClient\Tools\Profiles;

use dhope0000\LXDClient\Objects\HostsCollection;

class CreateProfile
{
    public function createOnHosts(
        HostsCollection $hosts,
        string $name,
        string $description = "",
        array $config = null,
        array $devices = null
    ) {
        foreach ($hosts as $host) {
            $host->profiles->create($name, $description, $config, $devices);
        }
        return true;
    }
}
