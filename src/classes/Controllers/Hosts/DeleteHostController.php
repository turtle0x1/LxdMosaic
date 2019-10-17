<?php
namespace dhope0000\LXDClient\Controllers\Hosts;

use dhope0000\LXDClient\Model\Hosts\DeleteHost;

class DeleteHostController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(DeleteHost $deleteHost)
    {
        $this->deleteHost = $deleteHost;
    }

    public function delete(int $hostId)
    {
        $this->deleteHost->delete($hostId);
        return ["state"=>"success", "message"=>"Deleted host"];
    }
}
