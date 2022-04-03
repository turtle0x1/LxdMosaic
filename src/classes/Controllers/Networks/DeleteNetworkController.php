<?php

namespace dhope0000\LXDClient\Controllers\Networks;

use dhope0000\LXDClient\Tools\Networks\DeleteNetwork;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class DeleteNetworkController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(DeleteNetwork $deleteNetwork)
    {
        $this->deleteNetwork = $deleteNetwork;
    }
    /**
     * @Route("/api/Networks/DeleteNetworkController/delete", methods={"POST"}, name="Delete Network")
     */
    public function delete(Host $host, $network)
    {
        $this->deleteNetwork->delete($host, $network);
        return ["state"=>"success", "message"=>"Deleted network"];
    }
}
