<?php

namespace dhope0000\LXDClient\Controllers\Storage\Volumes;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Storage\Volumes\CreateVolume;
use Symfony\Component\Routing\Attribute\Route;

class CreateStorageVolumeController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly CreateVolume $createVolume
    ) {
    }

    #[Route(path: '/api/Storage/Volumes/CreateStorageVolumeController/create', name: 'Create storage volume', methods: ['POST'])]
    public function create(Host $hostId, string $pool, string $name, array $config)
    {
        $this->createVolume->create($hostId, $pool, $name, $config);
        return [
            'state' => 'success',
            'message' => 'Created Volume',
        ];
    }
}
