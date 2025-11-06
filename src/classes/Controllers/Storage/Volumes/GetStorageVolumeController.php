<?php

namespace dhope0000\LXDClient\Controllers\Storage\Volumes;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Storage\Volumes\GetVolume;
use Symfony\Component\Routing\Attribute\Route;

class GetStorageVolumeController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly GetVolume $getVolume
    ) {
    }

    #[Route(path: '/api/Storage/Volumes/GetStorageVolumeController/get', name: 'Get storage volume info', methods: ['POST'])]
    public function get(int $userId, Host $hostId, string $pool, string $path, string $project)
    {
        return $this->getVolume->get($userId, $hostId, $pool, $path, $project);
    }
}
