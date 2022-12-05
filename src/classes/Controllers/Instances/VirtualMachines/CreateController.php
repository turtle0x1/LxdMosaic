<?php

namespace dhope0000\LXDClient\Controllers\Instances\VirtualMachines;

use dhope0000\LXDClient\Tools\Instances\VirtualMachines\CreateVirutalMachine;
use dhope0000\LXDClient\Objects\HostsCollection;

class CreateController
{
    private CreateVirutalMachine $createVirutalMachine;

    public function __construct(CreateVirutalMachine $createVirutalMachine)
    {
        $this->createVirutalMachine = $createVirutalMachine;
    }

    public function create(
        string $name,
        string $username,
        HostsCollection $hostIds,
        array $imageDetails,
        bool $start,
        array $config = []
    ) {
        $response = $this->createVirutalMachine->create(
            $name,
            $username,
            $hostIds,
            $imageDetails,
            $start,
            $config
        );

        return ["state"=>"success", "message"=>"Creating VM", "lxdResponse"=>$response];
    }
}
