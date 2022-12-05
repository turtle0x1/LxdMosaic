<?php

namespace dhope0000\LXDClient\Controllers\Storage\Volumes;

use dhope0000\LXDClient\Tools\Storage\Volumes\CreateVolume;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class CreateStorageVolumeController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private CreateVolume $createVolume;

    public function __construct(CreateVolume $createVolume)
    {
        $this->createVolume = $createVolume;
    }
    /**
     * @Route("", name="Create storage volume")
     */
    public function create(Host $hostId, string $pool, string $name, array $config) :array
    {
        $this->createVolume->create($hostId, $pool, $name, $config);
        return ["state"=>"success", "message"=>"Created Volume"];
    }
}
