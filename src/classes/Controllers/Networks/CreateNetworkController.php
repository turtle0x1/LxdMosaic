<?php

namespace dhope0000\LXDClient\Controllers\Networks;

use dhope0000\LXDClient\Tools\Networks\CreateNetwork;
use dhope0000\LXDClient\Objects\HostsCollection;
use Symfony\Component\Routing\Annotation\Route;

class CreateNetworkController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $createNetwork;

    public function __construct(CreateNetwork $createNetwork)
    {
        $this->createNetwork = $createNetwork;
    }
    /**
     * @Route("/api/Networks/CreateNetworkController/create", name="Create Network", methods={"POST"})
     */
    public function create(HostsCollection $hosts, string $name, string $description = "", array $config = [])
    {
        $this->createNetwork->create($hosts, $name, $description, $config);
        return ["state"=>"success", "message"=>"Created network"];
    }
}
