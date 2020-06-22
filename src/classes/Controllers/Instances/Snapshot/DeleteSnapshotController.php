<?php
namespace dhope0000\LXDClient\Controllers\Instances\Snapshot;

use dhope0000\LXDClient\Tools\Instances\Snapshot\DeleteSnapshot;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class DeleteSnapshotController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(DeleteSnapshot $deleteSnapshot)
    {
        $this->deleteSnapshot = $deleteSnapshot;
    }
    /**
     * @Route("", name="Delete Instance Snapshot")
     */
    public function deleteSnapshot(Host $host, string $container, string $snapshotName)
    {
        $lxdResponse = $this->deleteSnapshot->deleteSnapshot(
            $host,
            $container,
            $snapshotName
        );
        return [
            "state"=>"success",
            "message"=>"Removing Snapshot",
            "lxdResponse"=>$lxdResponse
        ];
    }
}
