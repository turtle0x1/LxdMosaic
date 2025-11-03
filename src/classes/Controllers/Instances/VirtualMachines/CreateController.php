<?php

namespace dhope0000\LXDClient\Controllers\Instances\VirtualMachines;

use dhope0000\LXDClient\Tools\Instances\VirtualMachines\CreateVirutalMachine;
use dhope0000\LXDClient\Objects\HostsCollection;
use Symfony\Component\Routing\Annotation\Route;

class CreateController
{
    private $createVirutalMachine;
    
    public function __construct(CreateVirutalMachine $createVirutalMachine)
    {
        $this->createVirutalMachine = $createVirutalMachine;
    }

    /**
     * @Route("/api/Instances/VirtualMachines/CreateController/create", name="api_instances_virtualmachines_createcontroller_create", methods={"POST"})
     */
    public function create(
        string $name,
        string $username,
        HostsCollection $hostIds,
        bool $start,
        array $imageDetails = [],
        array $config = []
    ) {
        $response = $this->createVirutalMachine->create(
            $name,
            $username,
            $hostIds,
            $start,
            $imageDetails,
            $config
        );

        return ["state"=>"success", "message"=>"Creating VM", "lxdResponse"=>$response];
    }
}
