<?php

namespace dhope0000\LXDClient\Controllers\Networks;

use dhope0000\LXDClient\Tools\Networks\DeleteNetwork;

class DeleteNetworkController
{
    public function __construct(DeleteNetwork $deleteNetwork)
    {
        $this->deleteNetwork = $deleteNetwork;
    }

    public function delete(int $hostId, $network){
        $this->deleteNetwork->delete($hostId, $network);
        return ["state"=>"success", "message"=>"Deleted network"];
    }
}
