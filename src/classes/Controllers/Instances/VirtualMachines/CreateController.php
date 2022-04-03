<?php

namespace dhope0000\LXDClient\Controllers\Instances\VirtualMachines;

use dhope0000\LXDClient\Tools\Instances\VirtualMachines\CreateVirutalMachine;
use dhope0000\LXDClient\Objects\HostsCollection;
use Symfony\Component\Routing\Annotation\Route;

class CreateController
{
    public function __construct(CreateVirutalMachine $createVirutalMachine)
    {
        $this->createVirutalMachine = $createVirutalMachine;
    }
    /**
     * @Route("/api/Instances/VirtualMachines/CreateController/create", methods={"POST"}, name="Create a virtual machine")
     */
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
