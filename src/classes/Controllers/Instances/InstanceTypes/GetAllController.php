<?php

namespace dhope0000\LXDClient\Controllers\Instances\InstanceTypes;

use dhope0000\LXDClient\Tools\Instances\InstanceTypes\GetInstanceTypes;
use Symfony\Component\Routing\Attribute\Route;

class GetAllController
{
    public function __construct(
        private readonly GetInstanceTypes $getInstanceTypes
    ) {
    }

    #[Route(path: '/api/Instances/InstanceTypes/GetAllController/getAll', name: 'api_instances_instancetypes_getallcontroller_getall', methods: ['POST'])]
    public function getAll()
    {
        return $this->getInstanceTypes->getGroupedByProvider();
    }
}
