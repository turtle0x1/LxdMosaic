<?php

namespace dhope0000\LXDClient\Controllers\Storage\Volumes;

use dhope0000\LXDClient\Tools\Storage\Volumes\GetVolumes;
use Symfony\Component\Routing\Annotation\Route;
use dhope0000\LXDClient\Objects\Host;

class GetHostStorageVolumesController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private GetVolumes $getVolumes;

    public function __construct(GetVolumes $getVolumes)
    {
        $this->getVolumes = $getVolumes;
    }
    /**
     * @Route("", name="Get all storage volumes on host")
     */
    public function get(int $userId, Host $hostId)
    {
        return $this->getVolumes->get($userId, $hostId);
    }
}
