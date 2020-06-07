<?php

namespace dhope0000\LXDClient\Controllers\Networks;

use dhope0000\LXDClient\Tools\Networks\DeleteNetwork;
use dhope0000\LXDClient\Objects\Host;

class DeleteNetworkController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(DeleteNetwork $deleteNetwork)
    {
        $this->deleteNetwork = $deleteNetwork;
    }

    public function delete(Host $host, $network)
    {
        $this->deleteNetwork->delete($host, $network);
        return ["state"=>"success", "message"=>"Deleted network"];
    }
}
