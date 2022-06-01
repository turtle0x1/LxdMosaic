<?php

namespace dhope0000\LXDClient\Controllers\Storage\Volumes;

use dhope0000\LXDClient\Tools\Storage\Volumes\GetVolume;
use Symfony\Component\Routing\Annotation\Route;
use dhope0000\LXDClient\Objects\Host;

class GetStorageVolumeController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $getVolume;
    
    public function __construct(GetVolume $getVolume)
    {
        $this->getVolume = $getVolume;
    }
    /**
     * @Route("", name="Get storage volume info")
     */
    public function get(int $userId, Host $hostId, string $pool, string $path, string $project)
    {
        return $this->getVolume->get($userId, $hostId, $pool, $path, $project);
    }
}
