<?php
namespace dhope0000\LXDClient\Controllers\Containers;

use dhope0000\LXDClient\Model\Containers\DeleteContainer;

class DeleteContainerController
{
    public function __construct(DeleteContainer $deleteContainer)
    {
        $this->deleteContainer =  $deleteContainer;
    }

    public function deleteContainer($host, $container)
    {
        $this->deleteContainer->delete($host, $container);
        return ["state"=>"success", "message"=>"Deleting $host/$container"];
    }
}
