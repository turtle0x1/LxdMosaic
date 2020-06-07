<?php

namespace dhope0000\LXDClient\Tools\Networks;

use dhope0000\LXDClient\Objects\Host;

class DeleteNetwork
{
    public function delete(Host $host, string $network)
    {
        return $host->networks->remove($network);
    }
}
