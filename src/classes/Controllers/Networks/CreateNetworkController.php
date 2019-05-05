<?php

namespace dhope0000\LXDClient\Controllers\Networks;

use dhope0000\LXDClient\Tools\Networks\CreateNetwork;

class CreateNetworkController
{
    public function __construct(CreateNetwork $createNetwork)
    {
        $this->createNetwork = $createNetwork;
    }

    public function create(array $hosts, string $name, string $description = "", array $config = []){
        $this->createNetwork->create($hosts, $name, $description, $config);
        return ["state"=>"success", "message"=>"Created network"];
    }
}
