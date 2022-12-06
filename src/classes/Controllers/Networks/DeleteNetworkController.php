<?php

namespace dhope0000\LXDClient\Controllers\Networks;

use dhope0000\LXDClient\Tools\Networks\DeleteNetwork;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class DeleteNetworkController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private DeleteNetwork $deleteNetwork;

    public function __construct(DeleteNetwork $deleteNetwork)
    {
        $this->deleteNetwork = $deleteNetwork;
    }
    /**
     * @Route("", name="Delete Network")
     */
    public function delete(Host $host, string $network) :array
    {
        $this->deleteNetwork->delete($host, $network);
        return ["state"=>"success", "message"=>"Deleted network"];
    }
}
