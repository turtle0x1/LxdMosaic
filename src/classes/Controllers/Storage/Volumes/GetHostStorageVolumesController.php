<?php

namespace dhope0000\LXDClient\Controllers\Storage\Volumes;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Storage\Volumes\GetVolumes;
use Symfony\Component\Routing\Attribute\Route;

class GetHostStorageVolumesController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly GetVolumes $getVolumes
    ) {
    }

    #[Route(path: '/api/Storage/Volumes/GetHostStorageVolumesController/get', name: 'Get all storage volumes on host', methods: ['POST'])]
    public function get(int $userId, Host $hostId)
    {
        return $this->getVolumes->get($userId, $hostId);
    }
}
