<?php

namespace dhope0000\LXDClient\Controllers\Networks;

use dhope0000\LXDClient\Objects\HostsCollection;
use dhope0000\LXDClient\Tools\Networks\CreateNetwork;
use Symfony\Component\Routing\Attribute\Route;

class CreateNetworkController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly CreateNetwork $createNetwork
    ) {
    }

    #[Route(path: '/api/Networks/CreateNetworkController/create', name: 'Create Network', methods: ['POST'])]
    public function create(HostsCollection $hosts, string $name, string $description = '', array $config = [])
    {
        $this->createNetwork->create($hosts, $name, $description, $config);
        return [
            'state' => 'success',
            'message' => 'Created network',
        ];
    }
}
