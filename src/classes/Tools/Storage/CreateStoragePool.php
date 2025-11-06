<?php

namespace dhope0000\LXDClient\Tools\Storage;

use dhope0000\LXDClient\Objects\HostsCollection;

class CreateStoragePool
{
    public function create(HostsCollection $hosts, string $name, string $driver, array $config)
    {
        if ($driver === 'dir' && isset($config['size'])) {
            unset($config['size']);
        }

        foreach ($hosts as $host) {
            $host->storage->create($name, $driver, $config);
        }
        return true;
    }
}
