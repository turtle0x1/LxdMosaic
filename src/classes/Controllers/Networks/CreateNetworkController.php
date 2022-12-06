<?php

namespace dhope0000\LXDClient\Controllers\Networks;

use dhope0000\LXDClient\Tools\Networks\CreateNetwork;
use dhope0000\LXDClient\Objects\HostsCollection;
use Symfony\Component\Routing\Annotation\Route;

class CreateNetworkController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private CreateNetwork $createNetwork;

    public function __construct(CreateNetwork $createNetwork)
    {
        $this->createNetwork = $createNetwork;
    }
    /**
     * @Route("", name="Create Network")
     */
    public function create(HostsCollection $hosts, string $name, string $description = "", array $config = []) :array
    {
        $this->createNetwork->create($hosts, $name, $description, $config);
        return ["state"=>"success", "message"=>"Created network"];
    }
}
