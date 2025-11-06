<?php

namespace dhope0000\LXDClient\Tools\Instances\Volumes;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;

class AttachVolumes
{
    public function __construct(
        private readonly ValidatePermissions $validatePermissions
    ) {
    }

    public function attach(int $userId, Host $host, string $container, array $volume, string $name, string $path)
    {
        $this->validateVolume($volume);
        $this->validatePermissions->canAccessHostProjectOrThrow($userId, $host->getHostId(), $volume['project']);

        $instanceConfig = $host->instances->info($container);

        if (isset($instanceConfig['devices'][$name])) {
            throw new \Exception('Already have a device with this name', 1);
        }

        $instanceConfig['devices'][$name] = [
            'pool' => $volume['pool'],
            'source' => $volume['name'],
            'type' => 'disk',
            'path' => $path,
        ];

        $result = $host->instances->replace($container, $instanceConfig, true);

        if (isset($result['err']) && $result['err'] !== '') {
            throw new \Exception($result['err'], 1);
        }
    }

    private function validateVolume(array $volume)
    {
        if (!isset($volume['name'])) {
            throw new \Exception('Missing volume name', 1);
        } elseif (!isset($volume['pool'])) {
            throw new \Exception('Missing volume pool', 1);
        } elseif (!isset($volume['project'])) {
            throw new \Exception('Missing volume project', 1);
        }
    }
}
