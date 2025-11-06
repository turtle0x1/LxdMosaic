<?php

namespace dhope0000\LXDClient\Controllers\Storage\Volumes;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Storage\Volumes\DeleteVolume;
use Symfony\Component\Routing\Attribute\Route;

class DeleteStorageVolumeController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly DeleteVolume $deleteVolume
    ) {
    }

    #[Route(path: '/api/Storage/Volumes/DeleteStorageVolumeController/delete', name: 'Delete storage volume', methods: ['POST'])]
    public function delete(Host $hostId, string $pool, string $path)
    {
        $this->deleteVolume->delete($hostId, $pool, $path);
        return [
            'state' => 'success',
            'message' => 'Deleted Volume',
        ];
    }
}
