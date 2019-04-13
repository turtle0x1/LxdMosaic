<?php
namespace dhope0000\LXDClient\Controllers\Containers;

use dhope0000\LXDClient\Tools\Containers\DeleteContainer;

class DeleteContainerController
{
    public function __construct(DeleteContainer $deleteContainer)
    {
        $this->deleteContainer =  $deleteContainer;
    }

    public function deleteContainer(int $hostId, string $container)
    {
        $this->deleteContainer->delete($hostId, $container);
        return ["state"=>"success", "message"=>"Deleting $host/$container"];
    }
}
