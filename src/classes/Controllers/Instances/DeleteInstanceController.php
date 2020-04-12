<?php
namespace dhope0000\LXDClient\Controllers\Instances;

use dhope0000\LXDClient\Tools\Instances\DeleteInstance;

class DeleteInstanceController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(DeleteInstance $deleteInstance)
    {
        $this->deleteInstance =  $deleteInstance;
    }

    public function delete(
        int $hostId,
        string $container,
        string $alias = null
    ) {
        $this->deleteInstance->delete($hostId, $container);
        return ["state"=>"success", "message"=>"Deleting $alias/$container"];
    }
}
