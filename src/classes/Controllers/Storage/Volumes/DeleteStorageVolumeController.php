<?php

namespace dhope0000\LXDClient\Controllers\Storage\Volumes;

use dhope0000\LXDClient\Tools\Storage\Volumes\DeleteVolume;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class DeleteStorageVolumeController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $deleteVolume;
    
    public function __construct(DeleteVolume $deleteVolume)
    {
        $this->deleteVolume = $deleteVolume;
    }
    /**
     * @Route("", name="Delete storage volume")
     */
    public function delete(Host $hostId, string $pool, string $path)
    {
        $this->deleteVolume->delete($hostId, $pool, $path);
        return ["state"=>"success", "message"=>"Deleted Volume"];
    }
}
