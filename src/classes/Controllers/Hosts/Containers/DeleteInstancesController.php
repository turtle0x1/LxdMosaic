<?php
namespace dhope0000\LXDClient\Controllers\Hosts\Containers;

use dhope0000\LXDClient\Tools\Instances\DeleteInstances;

class DeleteInstancesController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(DeleteInstances $deleteInstances)
    {
        $this->deleteInstances = $deleteInstances;
    }
    public function delete(int $hostId, array $containers)
    {
        $this->deleteInstances->delete($hostId, $containers);
        return ["state"=>"success", "message"=>"Delete Containers"];
    }
}
