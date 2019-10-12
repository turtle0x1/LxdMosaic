<?php
namespace dhope0000\LXDClient\Controllers\Hosts\Containers;

use dhope0000\LXDClient\Tools\Containers\DeleteContainers;

class DeleteContainersController
{
    public function __construct(DeleteContainers $deleteContainers)
    {
        $this->deleteContainers = $deleteContainers;
    }
    public function delete(int $hostId, array $containers)
    {
        $this->deleteContainers->delete($hostId, $containers);
        return ["state"=>"success", "message"=>"Delete Containers"];
    }
}
