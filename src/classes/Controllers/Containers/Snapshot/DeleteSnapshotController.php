<?php
namespace dhope0000\LXDClient\Controllers\Containers\Snapshot;

use dhope0000\LXDClient\Model\Containers\Snapshot\DeleteSnapshot;

class DeleteSnapshotController
{
    public function __construct(DeleteSnapshot $deleteSnapshot)
    {
        $this->deleteSnapshot = $deleteSnapshot;
    }

    public function deleteSnapshot($host, $container, $snapshotName)
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
