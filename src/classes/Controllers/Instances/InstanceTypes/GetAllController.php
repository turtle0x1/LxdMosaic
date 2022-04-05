<?php

namespace dhope0000\LXDClient\Controllers\Instances\InstanceTypes;

use dhope0000\LXDClient\Tools\Instances\InstanceTypes\GetInstanceTypes;
use Symfony\Component\Routing\Annotation\Route;

class GetAllController
{
    public function __construct(GetInstanceTypes $getInstanceTypes)
    {
        $this->getInstanceTypes = $getInstanceTypes;
    }
    /**
     * @Route("/api/Instances/InstanceTypes/GetAllController/getAll", methods={"POST"}, name="Get all instance types grouped by provider", options={"rbac" = "lxdmosaic.instaceTypes.providers.type.read"})
     */
    public function getAll()
    {
        return $this->getInstanceTypes->getGroupedByProvider();
    }
}
