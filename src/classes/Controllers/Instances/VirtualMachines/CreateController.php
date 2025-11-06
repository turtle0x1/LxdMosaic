<?php

namespace dhope0000\LXDClient\Controllers\Instances\VirtualMachines;

use dhope0000\LXDClient\Objects\HostsCollection;
use dhope0000\LXDClient\Tools\Instances\VirtualMachines\CreateVirutalMachine;
use Symfony\Component\Routing\Attribute\Route;

class CreateController
{
    public function __construct(
        private readonly CreateVirutalMachine $createVirutalMachine
    ) {
    }

    #[Route(path: '/api/Instances/VirtualMachines/CreateController/create', name: 'api_instances_virtualmachines_createcontroller_create', methods: ['POST'])]
    public function create(
        string $name,
        string $username,
        HostsCollection $hostIds,
        bool $start,
        array $imageDetails = [],
        array $config = []
    ) {
        $response = $this->createVirutalMachine->create($name, $username, $hostIds, $start, $imageDetails, $config);

        return [
            'state' => 'success',
            'message' => 'Creating VM',
            'lxdResponse' => $response,
        ];
    }
}
