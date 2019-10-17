<?php
namespace dhope0000\LXDClient\Controllers\Containers\Snapshot;

use dhope0000\LXDClient\Tools\Containers\Snapshot\DeleteSnapshot;

class DeleteSnapshotController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(DeleteSnapshot $deleteSnapshot)
    {
        $this->deleteSnapshot = $deleteSnapshot;
    }

    public function deleteSnapshot(int $hostId, string $container, string $snapshotName)
    {
        $lxdResponse = $this->deleteSnapshot->deleteSnapshot(
            $hostId,
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
